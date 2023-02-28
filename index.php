<?php
$search = @$_GET['search'];
// If there is a "search" parameter in the url, the folders of the current search are displayed in JSON, if not, the initial page that performs the search is displayed. This way we use the current file as home screen and backend
if ($search != "") {
    $results = [];

    $dir = './'; // directory path you want to list
    $files = scandir($dir); // returns all files and folders in the directory

    // loop through files and folders
    foreach ($files as $file) {
        // checks if it's a folder, if it's part of the current search, if it's not the current directory or the parent directory, and if it's not a default xampp folder
        if (is_dir($dir . $file) && (strpos($file, $search) !== false || $search == 'all') && $file != '.' && $file != '..' && $file != 'xampp' && $file != 'webalizer' && $file != 'dashboard' && $file != 'img') {
            $result = [
                "icon" => "",
                "file" => $file
            ];
            $results[] = $result;
        }
    }

    // Format the output to be displayed in JSON as an API
    echo json_encode($results);
} else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="icon" href='data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" fill="%23FF79C6" viewBox="0 0 16 16"> <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/> <path d="M6.854 4.646a.5.5 0 0 1 0 .708L4.207 8l2.647 2.646a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 0 1 .708 0zm2.292 0a.5.5 0 0 0 0 .708L11.793 8l-2.647 2.646a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708 0z"/> </svg>'>

        <title>Projects</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="./style.css">
    </head>

    <body>
        <div class="container">
            <div class="list-folders">
                <input type="text" id="txtSearch" placeholder="Searchi by a project...">

                <!-- <a href="#" class="folder" target="_blank">Teste</a> -->

                <!-- The page starts with a loader until the initial search is done -->
                <div class="spinner-content">
                    <div class="spinner"></div>
                </div>
            </div>
        </div>

        <script>
            const listFolders = document.getElementsByClassName('list-folders')[0];
            const fetchData = async (search = 'all') => {
                try {
                    const response = await fetch(`./?search=${search}`);
                    const data = await response.json();

                    data.forEach(item => {
                        listFolders.innerHTML += `<a href="./${item.file}" class="folder" target="_blank">${item.file}</a>`;
                    });

                    document.getElementsByClassName('spinner-content')[0].remove();
                } catch (error) {
                    console.error(error);
                    listFolders.innerHTML = 'Error';
                }
            }

            window.onload = () => {
                fetchData();
            };


            document.addEventListener('keydown', event => {
                // Check if the 'CTRL' keys for windows and linux or 'Command' for MAC were pressed simultaneously with the 'F' key
                if ((event.ctrlKey || event.metaKey) && event.keyCode == 70) {
                    event.preventDefault(); // Prevents the default browser search window from being displayed

                    // Focus on the search field
                    document.getElementById('txtSearch').focus();
                }
            });
        </script>
    </body>

    </html>
<?php
}
?>