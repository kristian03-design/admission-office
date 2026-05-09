<?php
$file = __DIR__ . '/../public/css/home-page.css';
$css = file_get_contents($file);

// The corrupted section: after .modal-close closing brace, 
// the orphaned .guide-step properties appear immediately.
// We need to find and fix this.

$bad = "  transition: background .2s;\r\n}\r\n  border-radius: 50%;";

$good = "  transition: background .2s;\r\n}\r\n\r\n.modal-close:hover {\r\n  background: rgba(255, 255, 255, .2);\r\n}\r\n\r\n.modal-body {\r\n  padding: 2rem 2.5rem 3rem;\r\n  overflow-y: auto;\r\n  max-height: calc(90vh - 70px);\r\n}\r\n\r\n.guide-section {\r\n  margin-bottom: 2.5rem;\r\n}\r\n\r\n.guide-section:last-child {\r\n  margin-bottom: 0;\r\n}\r\n\r\n.guide-category {\r\n  font-family: 'Cormorant Garamond', serif;\r\n  font-size: 1.25rem;\r\n  font-weight: 700;\r\n  color: var(--navy);\r\n  display: flex;\r\n  align-items: center;\r\n  gap: .75rem;\r\n  margin-bottom: 1.25rem;\r\n  padding-bottom: .5rem;\r\n  border-bottom: 2px solid var(--gold-pale);\r\n}\r\n\r\n.guide-category i,\r\n.guide-category svg {\r\n  width: 24px;\r\n  height: 24px;\r\n  flex-shrink: 0;\r\n}\r\n\r\n.guide-list {\r\n  display: flex;\r\n  flex-direction: column;\r\n  gap: 1.25rem;\r\n}\r\n\r\n.guide-item {\r\n  display: flex;\r\n  gap: 1rem;\r\n}\r\n\r\n.guide-step {\r\n  width: 24px;\r\n  height: 24px;\r\n  border-radius: 50%;";

if (strpos($css, $bad) !== false) {
    $new = str_replace($bad, $good, $css);
    file_put_contents($file, $new);
    echo "FIXED: Corrupted CSS section restored successfully.\n";
} else {
    // Try with LF only
    $bad_lf = "  transition: background .2s;\n}\n  border-radius: 50%;";
    $good_lf = str_replace("\r\n", "\n", $good);
    if (strpos($css, $bad_lf) !== false) {
        $new = str_replace($bad_lf, $good_lf, $css);
        file_put_contents($file, $new);
        echo "FIXED (LF): Corrupted CSS section restored successfully.\n";
    } else {
        echo "Pattern not found. Showing context around '.modal-close':\n";
        $pos = strpos($css, '.modal-close {');
        if ($pos !== false) {
            echo substr($css, $pos, 400);
        }
    }
}
