<?php
function prikaziFoldereIFajlove($dir, $nivo = 0, $maksimalniNivo = 3, $parentFolder = '') {
    if (!is_dir($dir) || $nivo > $maksimalniNivo) {
        return;
    }

    $entries = scandir($dir);

    echo "<ul style='margin-left: ".(10 + $nivo * 10)."px;'>";
    foreach ($entries as $entry) {
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        $fullPath = $dir . DIRECTORY_SEPARATOR . $entry;

        if (is_dir($fullPath)) {
            $folderId = 'dir-' . md5($fullPath);
            echo "<li class='search-item'>
                    <i class='fa fa-folder' id='icon-$folderId' onclick='toggleFolder(\"$folderId\")' style='cursor: pointer; color: #FFD700;'></i>
                    <strong><a href='#' onclick='toggleFolder(\"$folderId\")' style='text-decoration: none;'>$entry</a></strong>
                  </li>";
            echo "<ul id='$folderId' style='display: none;'>";
            prikaziFoldereIFajlove($fullPath, $nivo + 1, $maksimalniNivo, $entry);
            echo "</ul>";
        } else if (preg_match('/\.(mp3|mp4)$/i', $entry)) {
            $fileType = (preg_match('/\.mp3$/i', $entry)) ? 'audio' : 'video';
            $iconClass = ($fileType === 'audio') ? 'fa fa-music' : 'fa fa-video';
            $iconColor = ($fileType === 'audio') ? '#007BFF' : '#1E90FF'; // Plave nijanse
            $fileSize = filesize($fullPath) / (1024 * 1024); // Veličina fajla u megabajtima
            echo "<li class='search-item' data-type='$fileType' data-path='$fullPath' data-folder='$parentFolder'>
                    <i class='$iconClass' style='margin-right: 3px; color: $iconColor;'></i>
                    <a href='#' onclick='playMedia(\"$fullPath\", \"$fileType\", \"$entry\", \"$parentFolder\")' style='text-decoration: none;'>$entry</a>
                    <span style='margin-left: 5px;'>(".round($fileSize, 2)." MB)</span>
                  </li>";
        }
    }
    echo "</ul>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muzički i Video Plejer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            color: #333;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        li {
            margin: 2px 0;
        }
        .fa-folder {
            font-size: 18px;
            vertical-align: middle;
            margin-right: 5px;
            color: #FFD700;
        }
        .fa-music, .fa-video {
            font-size: 18px;
            vertical-align: middle;
            margin-right: 3px;
        }
        .fa-music {
            color: #007BFF;
        }
        .fa-video {
            color: #1E90FF;
        }
        #media-player {
            position: fixed;
            top: 10px;
            right: 10px;
            width: 300px;
            padding: 15px;
            border: 1px solid #ccc;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        audio, video {
            width: 100%;
            margin-top: 10px;
        }
        #track-title {
            margin-top: 10px;
            font-weight: bold;
            font-size: 14px;
            color: #444;
        }
        #equalizer {
            margin-top: 10px;
        }
        #equalizer h4 {
            color: #444;
            font-size: 1.1em;
        }
        .slider {
            width: 100%;
        }
        h1 {
            color: #005a9c;
        }
        h3 {
            font-size: 1.2em;
            color: #005a9c;
        }
        a {
            color: #005a9c;
        }
        #search-bar {
            margin: 20px 0;
            padding: 8px;
            width: 20%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .active-file {
            color: red;
            font-weight: bold;
        }
        .section {
            border: 1px solid #ccc;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            background-color: #fff;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/tone@14.8.12/build/Tone.js"></script>
</head>
<body>
<h1>Muzički i Video Plejer</h1>

<!-- Pretraga -->
<input type="text" id="search-bar" placeholder="Pretraži foldere i fajlove...">

<div class="section" id="media-player">
    <h3>Reprodukcija</h3>
    <audio id="audioPlayer" controls style="display: none;"></audio>
    <video id="videoPlayer" controls style="display: none;"></video>
    <div id="track-title" style="display: none;">
        <span id="folder-name" style="font-weight: normal; color: #666;"></span> / <span id="file-name">Nema aktivne reprodukcije</span>
    </div>

    <canvas id="equalizerCanvas" width="300" height="100" style="display: none; margin-top: 10px;"></canvas>

    <div id="equalizer" style="display: none;">
        <h4>Ekvilajzer</h4>
        <label for="gain">Gain:</label>
        <input type="range" id="gain" class="slider" min="0" max="12" step="0.5" value="5">
        <br>
        <label for="bass">Bass:</label>
        <input type="range" id="bass" class="slider" min="0" max="12" step="0.5" value="5">
        <br>
        <label for="treble">Treble:</label>
        <input type="range" id="treble" class="slider" min="0" max="12" step="0.5" value="5">
    </div>
</div>

<div class="section">
    <?php
    // Početni direktorijum (promenite putanju do foldera)
//    $rootDir = './Antologija izvorne muzike';  // Promenite putanju do foldera sa vašim fajlovima
    $rootDir = './MOMO';
    prikaziFoldereIFajlove($rootDir);
    ?>
</div>

<script>
    let currentFileIndex = 0; // Indeks trenutnog fajla
    let activeFileElement = null; // Čuvanje trenutno aktivnog fajla
    const files = []; // Lista fajlova za reprodukciju

    // Popunite listu fajlova
    document.querySelectorAll('.search-item').forEach(item => {
    const filePath = item.getAttribute('data-path');
    if (filePath) {
        const fileType = item.getAttribute('data-type');
        const fileName = item.textContent; // Prikazuje ime fajla
        const folderName = item.getAttribute('data-folder'); // Uzima ime foldera
        files.push({path: filePath, type: fileType, name: fileName, folder: folderName, element: item}); // Dodajte element
    }
    });
    let currentPath = null; // Globalna promenljiva za čuvanje trenutnog puta
    function playMedia(path, type, fileName, folderName) {
        const audioPlayer = document.getElementById('audioPlayer');
        const videoPlayer = document.getElementById('videoPlayer');
        const trackTitle = document.getElementById('track-title');
        const fileNameElement = document.getElementById('file-name');
        const folderNameElement = document.getElementById('folder-name');
        const equalizer = document.getElementById('equalizer');
        const canvas = document.getElementById('equalizerCanvas');
        const canvasCtx = canvas.getContext('2d');
// Postavljanje trenutnog puta za aktivni fajl
        currentPath = path;
        // Resetovanje aktivnog fajla
        if (activeFileElement) {
            activeFileElement.classList.remove('active-file');
        }
        console.log ('xxxxxxxxxx', activeFileElement);
        // Obeležavanje aktivnog fajla
        const file = files.find(f => f.path === path);
        console.log ('file', file);
        console.log ('path', path);
        console.log ('files ', files);
        if (file) {
            file.element.classList.add('active-file');
            activeFileElement = file.element;
        }

/*        // Uklanjamo crvenu boju sa prethodnog fajla
        if (activeFileElement) {
            activeFileElement.className = activeFileElement.className.replace(' active-file', '');
        }

        // Pronađi i oboji novi fajl koji se reprodukuje
        const file = files.find(f => f.path === path);
        if (file) {
            file.element.className += ' active-file'; // Dodaj klasu
            activeFileElement = file.element; // Postavi novi aktivni element
        }*/
        console.log ('name ', fileName);
        console.log ('folder ', folderName);
        // Prikazujemo naziv foldera i fajla
        folderNameElement.textContent = folderName || "Nema foldera"; // Postavi naziv foldera
        fileNameElement.textContent = fileName; // Postavi naziv fajla
        trackTitle.style.display = 'block'; // Prikazujemo naslov

        // Audio ili video reprodukcija
        if (type === 'audio') {
            audioPlayer.src = path;
            audioPlayer.style.display = 'block';
            videoPlayer.style.display = 'none';
            audioPlayer.play();
            equalizer.style.display = 'block'; // Prikazujemo ekvilajzer
            canvas.style.display = 'block'; // Prikazujemo canvas za grafički ekvilajzer
        } else if (type === 'video') {
            videoPlayer.src = path;
            videoPlayer.style.display = 'block';
            audioPlayer.style.display = 'none';
            videoPlayer.play();
            equalizer.style.display = 'none'; // Sakrivamo ekvilajzer za video
            canvas.style.display = 'none'; // Sakrivamo canvas za video
        }

        // Konfiguracija ekvilajzera
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const analyser = audioContext.createAnalyser();
        analyser.fftSize = 256; // Manji broj traka
        const gainNode = audioContext.createGain();
        const bassNode = audioContext.createBiquadFilter();
        bassNode.type = 'lowshelf';

        bassNode.frequency.value = 100; // Postavi nisku frekvenciju za bass
        bassNode.gain.value = 6; // Početno pojačanje za bass

        const trebleNode = audioContext.createBiquadFilter();
        trebleNode.type = 'highshelf';

        trebleNode.frequency.value = 3000; // Postavi visoku frekvenciju za treble
        trebleNode.gain.value = 6; // Početno pojačanje za treble

        const source = audioContext.createMediaElementSource(audioPlayer);
        source.connect(gainNode).connect(bassNode).connect(trebleNode).connect(analyser).connect(audioContext.destination);

        // Dodajte ove kontrole ispod konfiguracije ekvilajzera
        document.getElementById('gain').addEventListener('input', function() {
            gainNode.gain.value = this.value;
            alert (this.value);
        });

        document.getElementById('bass').addEventListener('input', function() {
            bassNode.frequency.value = 200; // Opseg basa
            bassNode.gain.value = this.value;
            alert (this.value);
        });

        document.getElementById('treble').addEventListener('input', function() {
            trebleNode.frequency.value = 3000; // Opseg visokih tonova
            trebleNode.gain.value = this.value;
            alert (this.value);
        });

        function drawEqualizer() {
            const bufferLength = analyser.frequencyBinCount;
            const dataArray = new Uint8Array(bufferLength);
            analyser.getByteFrequencyData(dataArray);

            canvasCtx.clearRect(0, 0, canvas.width, canvas.height);

            const barWidth = (canvas.width / bufferLength) * 2.5;
            let barHeight;
            let x = 0;

            for (let i = 0; i < bufferLength; i++) {
                barHeight = dataArray[i];
                const red = barHeight + 25;
                const green = 250 - barHeight;
                const blue = 50 + (barHeight / 2);

                // Prelazne boje za vizualizaciju traka
                canvasCtx.fillStyle = `rgb(${red}, ${green}, ${blue})`;
                canvasCtx.fillRect(x, canvas.height - barHeight / 2, barWidth, barHeight / 2);

                x += barWidth + 1;
            }

            requestAnimationFrame(drawEqualizer);
        }


        audioPlayer.onplay = function() {
            audioContext.resume();
            drawEqualizer();
        };

        audioPlayer.onended = function() {
            playNextMedia();
        };
    }

    // Funkcija za reprodukciju sledećeg fajla, uključujući prelazak na sledeći folder
    function playNextMedia() {
/*        currentFileIndex++;

        // Proverava da li smo na kraju liste fajlova
        if (currentFileIndex >= files.length) {
            currentFileIndex = 0; // Resetujemo na početak liste
        }*/
//        const currentFileIndex = findFileIndexByPath(currentPath);
        // Postavljanje `currentFileIndex` prema fajlu koji odgovara `path`
        const currentFileIndex = findFileIndexByPath(currentPath);
        alert (currentFileIndex);
//        const file = files[currentFileIndex];

        const nextFile = files[currentFileIndex];
        if (nextFile) {
            playMedia(nextFile.path, nextFile.type, nextFile.name, nextFile.folder);
        }
    }

    // Funkcija za pronalaženje indeksa fajla po path-u
    function findFileIndexByPath(path) {
        for (let i = 0; i < files.length; i++) {
            if (files[i].path === path) {
                i++
                return i; // Vraća indeks fajla ako je pronađen
            }
        }
        return -1; // Vraća -1 ako fajl nije pronađen
    }


    // Prelazimo na sledeći folder kada se završi reprodukcija trenutnog foldera
    function findNextInFolder(folderName) {

        for (let i = currentFileIndex + 1; i < files.length; i++) {
            if (files[i].folder !== folderName) {
                return i;
            } else
                return currentFileIndex;

        }
        return null; // Nema sledećeg foldera
    }

    function toggleFolder(folderId) {
        const folder = document.getElementById(folderId);
        folder.style.display = folder.style.display === "none" ? "block" : "none";
    }

    // Pretraga
    document.getElementById('search-bar').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        document.querySelectorAll('.search-item').forEach(item => {
            const text = item.textContent.toLowerCase();
        item.style.display = text.includes(searchTerm) ? 'block' : 'none';
    });
    });
</script>
</body>
</html>
