<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        header,
        footer {
            background-color: #f4f4f4;
            text-align: center;
            padding: 10px 0;
        }

        header h1,
        footer p {
            margin: 0;
        }
    </style>
</head>

<body>
    <header>
        <h1>Welcome to LOL Champions Sorter</h1>
    </header>

    <main>
        <button onclick="window.location.href='../../index.html'">Home</button>
        <button onclick="window.location.href='../../wordleSorter.html'">Wordle Sorter</button>

        <h1> Work in progress </h1>
        <h2>Select Two JSON (.txt) Files</h2>
        <input type="file" id="file1" accept=".txt,.json">
        <input type="file" id="file2" accept=".txt,.json">

        <button onclick="processFiles()">Compare & Combine</button>
        <pre id="output"></pre>

        <h2>Compare Champions from Two Local Files</h2>
        <button onclick="compareFiles()">Compare Files</button>
        <pre id="output"></pre>
    </main>

    <footer>
        <p>&copy; 2025 Sorter. All rights reserved.</p>
    </footer>

    <script src="JavaScript/championLocalDataSorter.js"></script>
    <script src="JavaScript/championDataSorter.js"></script>

</body>

</html>