<?php
if (isset($_POST['search-input'])) {
    $searchTerm = strtolower($_POST['search-input'] ?? ''); //coalescing operator

    searchCSV($searchTerm);
}

function searchCSV($searchTerm) {
    $file = fopen('containerData_ForGenius.csv', 'r');
    $matchingLines = [];

    while (($line = fgetcsv($file)) !== false) {
        if (strtolower($line[0]) === strtolower($searchTerm)) {
            $matchingLines[] = $line;
        }
    }

    fclose($file);

    displaySearchResults($matchingLines);
}

function displaySearchResults($results) {
    if (empty($results)) {
        echo '<p>No results found.</p>';
    } else {
        echo '<form method="post" action="update.php">';
        echo '<table id="myTable" class="display">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Cnumber</th>';
        echo '<th>Prefix</th>';
        echo '<th>Tcode</th>';
        echo '<th>Size</th>';
        echo '<th>Location</th>';
        echo '<th>Type</th>';
        echo '<th>Depot</th>';
        echo '<th>Status</th>';
        echo '<th>Client</th>';
        echo '<th>Invoice</th>';
        echo '<th>Supplier</th>';
        echo '<th>Acq Date</th>';
        echo '<th>D Date</th>';
        echo '<th>Rand Price</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($results as $rowIndex => $row) {
            echo '<tr>';
            foreach ($row as $columnIndex => $value) {
                echo '<td>';
                if ($columnIndex !== 0) {
                    echo '<input type="text" name="newValue" value="' . $value . '" data-row="' . $rowIndex . '" data-column="' . $columnIndex . '">';
                } else {
                    echo $value;
                }
                echo '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '<div>';
        echo '<button type="submit" name="save-button">Save Changes</button>';
        echo '</div>';
        echo '</form>';
    }
}
?>
