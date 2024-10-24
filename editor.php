<?php

require_once "header.php";
$directory = './';
$files = array_diff(scandir($directory), array('..', '.'));
$not_allowed_files = ['save.php', 'editor.php', '.idea', '.git', '.gitignore'];
$files = array_diff($files, $not_allowed_files);

$selected_file = isset($_POST['file']) && in_array($_POST['file'], $files) ? $_POST['file'] : 'index.php';
$filename = $directory . '/' . $selected_file;
$code = file_exists($filename) ? file_get_contents($filename) : '';
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.css">

<h1>Live Code Editor</h1>
<form method="POST" id="fileSelectForm">
    <label for="file">Select File:</label>
    <select name="file" id="file" onchange="document.getElementById('fileSelectForm').submit();">
        <?php foreach ($files as $file): ?>
            <option value="<?php echo $file; ?>" <?php echo ($file == $selected_file) ? 'selected' : ''; ?>>
                <?php echo ucfirst(basename($file, '.php')); ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<textarea id="code" name="code"><?php echo htmlspecialchars($code); ?></textarea>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/xml/xml.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
            lineNumbers: true,
            mode: "text/html",
            theme: "default"
        });

        setInterval(function () {
            var code = editor.getValue();
            fetch('save.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'code=' + encodeURIComponent(code) + '&file=' + encodeURIComponent("<?php echo $selected_file; ?>")
            }).then(response => {
                if (response.ok) {
                    console.log("Auto-saved successfully.");
                }
            }).catch(error => console.error('Error:', error));
        }, 2000);
    });
</script>


<?php

require_once "footer.php";

?>
