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
                <div class="update-result">
                    <?php
                        if (isset($_POST['save-button'])) {
                            // Get the updated values from the form
                            $updatedData = json_decode($_POST['newValue'], true);
                        
                            // Load the CSV data
                            $data = [];
                            $file = fopen('containerData_ForGenius.csv', 'r');
                            if ($file) {
                                while (($line = fgetcsv($file)) !== false) {
                                    $data[] = $line;
                                }
                                fclose($file);
                            } else {
                                echo "Failed to open the CSV file.";
                            }
                        
                            // Update the data array with the new values
                            if (is_array($updatedData)) {
                                foreach ($updatedData as $columnData) {
                                    if (is_array($columnData)) {
                                        $rowIndex = $columnData['row'];
                                        $columnIndex = $columnData['column'];
                                        $newValue = $columnData['value'];
                        
                                        $data[$rowIndex][$columnIndex] = $newValue;
                                    }
                                }
                            }
                        
                            // Save the updated data to the CSV file
                            $file = fopen('containerData_ForGenius.csv', 'w');
                            if ($file) {
                                foreach ($data as $row) {
                                    fputcsv($file, $row);
                                }
                                fclose($file);
                                echo "Changes saved successfully.";
                            } else {
                                echo "Failed to save changes.";
                            }
                        }   
                    ?>
                </div>
                <div class="back-button" onclick="goBack()">
                    <i class="back-icon">&larr;</i>
                    Back
                </div>
            </div>

            <?php include 'footer.php'; ?>
        </div>
        <script>
            function goBack() {
                window.history.back();
            }
    </script>
    </body>
</html>