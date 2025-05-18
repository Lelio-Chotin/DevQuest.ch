<?php

function parseLIO($content) {
    $lines = explode("\n", trim($content));
    $html = "";
    $listOpen = 0;
    $inCodeBlock = false;
    $codeBlockLang = "";
    $codeBlockContent = "";

    $inTable = false;
    $tableRows = [];

    $totalLines = count($lines);
    for ($i = 0; $i < $totalLines; $i++) {
        $line = $lines[$i];
        $trimmed = trim($line);

        // Bloc de code
        if (str_starts_with($trimmed, "'''")) {
            if (!$inCodeBlock) {
                $inCodeBlock = true;
                $codeBlockLang = substr($trimmed, 3);
                $codeBlockContent = "";
            } else {
                $escapedCode = htmlspecialchars($codeBlockContent, ENT_QUOTES, 'UTF-8');
                $class = $codeBlockLang ? " class=\"language-$codeBlockLang\"" : "";
                $langLabel = htmlspecialchars($codeBlockLang ?: "code");

                $html .= "<div class=\"code-container\">";
                $html .= "<div class=\"code-header\">
                            <span class=\"code-lang\">$langLabel</span>
                            <button class=\"copy-button\" onclick=\"copyCode(this)\">Copier</button>
                          </div>";
                $html .= "<pre><code$class>$escapedCode</code></pre>";
                $html .= "</div>\n";

                $inCodeBlock = false;
                $codeBlockLang = "";
            }
            continue;
        }

        if ($inCodeBlock) {
            $codeBlockContent .= $line . "\n";
            continue;
        }

        // Table Markdown
        if (preg_match('/^\|(.+)\|$/', $trimmed)) {
            if (!$inTable) {
                $inTable = true;
                $tableRows = [];
            }

            if (!preg_match('/^\|[-\s|]+\|$/', $trimmed)) {
                $tableRows[] = array_map('trim', explode('|', trim($trimmed, '|')));
            }

            $nextLine = $lines[$i + 1] ?? null;
            if ($nextLine === null || !preg_match('/^\|(.+)\|$/', trim($nextLine))) {
                // Render table
                if (count($tableRows) > 1) {
                    $html .= "<table class=\"lio-table\"><thead><tr>";
                    foreach ($tableRows[0] as $th) {
                        $html .= "<th>" . formatText($th) . "</th>";
                    }
                    $html .= "</tr></thead><tbody>";
                    for ($j = 1; $j < count($tableRows); $j++) {
                        $html .= "<tr>";
                        foreach ($tableRows[$j] as $td) {
                            $html .= "<td>" . formatText($td) . "</td>";
                        }
                        $html .= "</tr>";
                    }
                    $html .= "</tbody></table>\n";
                }
                $inTable = false;
                $tableRows = [];
            }
            continue;
        }

        // Listes
        if (str_starts_with($trimmed, "(")) {
            $html .= "<ul>";
            $listOpen++;
        } elseif (str_starts_with($trimmed, ")")) {
            if ($listOpen > 0) {
                $html .= "</ul>";
                $listOpen--;
            }
        } elseif (str_starts_with($trimmed, "- ")) {
            $html .= "<li>" . formatText(substr($trimmed, 2)) . "</li>";
        }

        // Citations et alertes
        elseif (str_starts_with($trimmed, "> ")) {
            $html .= "<blockquote>" . formatText(substr($trimmed, 2)) . "</blockquote>\n";
        } elseif (preg_match('/^\[!([a-z]+)\] (.+)$/', $line, $matches)) {
            $html .= '<blockquote class="box ' . $matches[1] . '">' . formatText($matches[2]) . '</blockquote>';
        }

        // Séparateur
        elseif (str_starts_with($trimmed, "---")) {
            $html .= "<hr>";
        }

        // Titres
        elseif (str_starts_with($trimmed, "### ")) {
            $html .= "<h3>" . formatText(substr($trimmed, 4)) . "</h3>\n";
        } elseif (str_starts_with($trimmed, "## ")) {
            $html .= "<h2>" . formatText(substr($trimmed, 3)) . "</h2>\n";
        } elseif (str_starts_with($trimmed, "# ")) {
            $html .= "<h1>" . formatText(substr($trimmed, 2)) . "</h1>\n";
        }

        // Paragraphe ou ligne vide
        elseif (strlen($trimmed) > 0) {
            $html .= "<p>" . formatText($trimmed) . "</p>\n";
        } else {
            $html .= "<br>";
        }
    }

    // Bloc de code non fermé
    if ($inCodeBlock) {
        $escapedCode = htmlspecialchars($codeBlockContent, ENT_QUOTES, 'UTF-8');
        $class = $codeBlockLang ? " class=\"language-$codeBlockLang\"" : "";
        $langLabel = htmlspecialchars($codeBlockLang ?: "code");

        $html .= "<div class=\"code-container\">";
        $html .= "<div class=\"code-header\">
                    <span class=\"code-lang\">$langLabel</span>
                    <button class=\"copy-button\" onclick=\"copyCode(this)\">Copier</button>
                  </div>";
        $html .= "<pre><code$class>$escapedCode</code></pre>";
        $html .= "</div>\n";
    }

    // Fermer listes ouvertes
    while ($listOpen > 0) {
        $html .= "</ul>";
        $listOpen--;
    }

    return $html;
}

function formatText($text) {
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
    $text = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $text);
    $text = preg_replace('/__(.+?)__/', '<u>$1</u>', $text);
    $text = preg_replace('/!\[(.+?)\]\((.+?)\)/', '<img src="$2" alt="$1">', $text);
    $text = preg_replace('/\[(.+?)\]\((.+?)\)/', '<a href="$2">$1</a>', $text);
    $text = preg_replace('/`(.+?)`/', '<code class="inline">$1</code>', $text);
    return $text;
}
?>
