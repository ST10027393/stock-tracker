<?php 

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Container Data Search</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://kit.fontawesome.com/35e1f2a27c.js" crossorigin="anonymous"></script>
</head>
<body>
	<div class="pagewrapper">
		<?php include 'header.php'; ?>

		<div class="container">
            <div class="search-bar">
                <form action="update.php" method="post" class="search-form">
                    <input type="text" name="search-input" id="search-input" placeholder="Enter a search term" required>
                    <button type="submit" name="search-button">Search</button>
                </form>
            </div>
            <div id="search-results">
        <?php
        // Function to save the updated data to the CSV file
        function saveCSV($data) {
            $file = fopen('containerData_ForGenius.csv', 'w'); // Open the file in write mode

            foreach ($data as $row) {
                fputcsv($file, $row); // Write each row to the CSV file
            }

            fclose($file); // Close the file
        }

        // Initialize the $data variable as an empty array
        $data = [];

        // Load the CSV data only if it hasn't been loaded before
        if (empty($data)) {
            // Read the CSV file
            $file = fopen('containerData_ForGenius.csv', 'r');
            if ($file) {
                while (($line = fgetcsv($file)) !== false) {
                    $data[] = $line; // Append each line as a row to the $data array
                }
                fclose($file);
            } else {
                echo "Failed to open the CSV file.";
            }
        }

        if (isset($_POST['search-button'])) {
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
      </div>
    </div>
        </div>
	
		<?php include 'footer.php'; ?>
	</div>
    <script>
  var form = document.querySelector('.search-form');
  form.addEventListener('submit', function(event) {
    event.preventDefault();

    var searchTerm = document.getElementById('search-input').value;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'search.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
      if (xhr.status >= 200 && xhr.status < 400) {
        document.getElementById('search-results').innerHTML = xhr.responseText;
      } else {
        console.error('An error occurred.');
      }
    };

    var params = 'search-input=' + encodeURIComponent(searchTerm);
    xhr.send(params);
  });
</script>

</body>
</html>