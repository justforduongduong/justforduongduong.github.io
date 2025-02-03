<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Media</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f5f5f5;
        }

        header {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 1rem 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .gift{
            padding: 0.5rem 1rem;
            background-color: cyan;
            color: white;
            border: none;
            border-radius: 2px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .container {
            max-width: 800px;
            width: 100%;
            padding: 1rem;
            box-sizing: border-box;
        }

        .upload-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .upload-form input[type="file"] {
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .upload-form button {
            padding: 0.5rem 1rem;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .upload-form button:hover {
            background-color: #45a049;
        }

        .media-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .media-item {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .media-item img, .media-item video {
            width: 100%;
            height: auto;
            transition: transform 0.3s;
        }

        .media-item img:hover {
            transform: scale(1.2);
        }

        .media-item video {
            max-height: 300px;
        }

        .media-item img {
            cursor: pointer;
        }

        .media-item .delete-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: red;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 0.2rem 0.5rem;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .media-item .delete-btn:hover {
            background: darkred;
        }

        .zoom-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .zoom-overlay img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .zoom-overlay .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: white;
            border: none;
            padding: 0.5rem 1rem;
            font-size: 1.2rem;
            cursor: pointer;
            border-radius: 5px;
        }

        .zoom-overlay .close-btn:hover {
            background: #f5f5f5;
        }

        .intro-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 2000;
            display: flex;
            justify-content: center;
            align-items: center;
            display: none;
        }

        .intro-video video {
            max-width: 100%;
            max-height: 100%;
            
        }

        .intro-video .skip-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: white;
            border: none;
            padding: 0.5rem 1rem;
            font-size: 1.2rem;
            cursor: pointer;
            border-radius: 5px;
            z-index: 2001;
        }

        .intro-video .skip-btn:hover {
            background: #f5f5f5;
        }

        .change-intro {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="intro-video" id="introVideo">
        <video controls id="introVideoElement">
            <source src="wait.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <button class="skip-btn" id="skipIntro">Skip</button>
    </div>

    <header>
        <h1>Upload Media</h1>
        
        <button type="submit" class="gift" id="gift"><h4>Ấn để nhận quà!!!</h4></button>

    </header>

    <div class="container">
        <form class="upload-form" id="uploadForm">
            <input type="file" id="mediaInput" accept="image/*,video/*" multiple>
            <button type="submit">Upload</button>
        </form>


        <div class="media-list" id="mediaList">
            <!-- Uploaded media will be displayed here -->
        </div>
    </div>

    <div class="zoom-overlay" id="zoomOverlay" style="display: none;">
        <button class="close-btn" id="closeZoom">&times;</button>
        <img id="zoomedImage" src="" alt="Zoomed">
    </div>

    <script>
        const uploadForm = document.getElementById('uploadForm');
        const mediaInput = document.getElementById('mediaInput');
        const mediaList = document.getElementById('mediaList');
        const zoomOverlay = document.getElementById('zoomOverlay');
        const zoomedImage = document.getElementById('zoomedImage');
        const closeZoom = document.getElementById('closeZoom');
        const introVideo = document.getElementById('introVideo');
        const skipIntro = document.getElementById('skipIntro');
        const introVideoElement = document.getElementById('introVideoElement');
        const changeIntro = document.getElementById('changeIntro');
        const gift = document.getElementById('gift');

        // Load stored media from localStorage on page load
        window.addEventListener('load', () => {
            const storedMedia = JSON.parse(localStorage.getItem('uploadedMedia')) || [];
            storedMedia.forEach(media => {
                displayMedia(media);
            });
        });

        skipIntro.addEventListener('click', () => {
            introVideo.style.display = 'none';
            introVideoElement.pause();
        });

        introVideoElement.addEventListener('ended', () => {
            introVideo.style.display = 'none';
        });

        uploadForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const files = Array.from(mediaInput.files);
            const storedMedia = JSON.parse(localStorage.getItem('uploadedMedia')) || [];

            files.forEach(file => {
                const reader = new FileReader();

                reader.onload = () => {
                    const mediaData = {
                        type: file.type,
                        src: reader.result
                    };
                    storedMedia.push(mediaData);
                    localStorage.setItem('uploadedMedia', JSON.stringify(storedMedia));
                    displayMedia(mediaData);
                };

                reader.readAsDataURL(file);
            });

            mediaInput.value = '';
        });

        function displayMedia(media) {
            const mediaItem = document.createElement('div');
            mediaItem.classList.add('media-item');

            if (media.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = media.src;
                img.addEventListener('click', () => zoomImage(media.src));
                mediaItem.appendChild(img);
            } else if (media.type.startsWith('video/')) {
                const video = document.createElement('video');
                video.src = media.src;
                video.controls = true;
                mediaItem.appendChild(video);
            }

            const deleteBtn = document.createElement('button');
            deleteBtn.classList.add('delete-btn');
            deleteBtn.textContent = 'X';
            deleteBtn.addEventListener('click', () => deleteMedia(mediaItem, media));
            mediaItem.appendChild(deleteBtn);

            mediaList.appendChild(mediaItem);
        }

        function deleteMedia(mediaItem, media) {
            mediaItem.remove();
            const storedMedia = JSON.parse(localStorage.getItem('uploadedMedia')) || [];
            const updatedMedia = storedMedia.filter(item => item.src !== media.src);
            localStorage.setItem('uploadedMedia', JSON.stringify(updatedMedia));
        }

        function zoomImage(src) {
            zoomedImage.src = src;
            zoomOverlay.style.display = 'flex';
        }

        function intro(){

            introVideo.style.display = "flex";

        }

        gift.addEventListener('click', () => {
            introVideo.style.display = "flex";
        });
        closeZoom.addEventListener('click', () => {
            zoomOverlay.style.display = 'none';
            zoomedImage.src = '';
        });

        changeIntro.addEventListener('click', () => {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = 'video/*';
            fileInput.addEventListener('change', () => {
                const file = fileInput.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = () => {
                        introVideoElement.src = reader.result;
                        localStorage.setItem('introVideo', reader.result);
                        alert('Intro video updated successfully!');
                    };
                    reader.readAsDataURL(file);
                }
            });
            fileInput.click();
        });

        // Load custom intro video if available
        const storedIntroVideo = localStorage.getItem('introVideo');
        if (storedIntroVideo) {
            introVideoElement.src = storedIntroVideo;
        }
    </script>
</body>
</html>
