function updatePreview() {
    const url = document.getElementById('image_url').value.trim();
    const img = document.getElementById('preview');
    const placeholder = 'https://firstbenefits.org/wp-content/uploads/2017/10/placeholder-1024x1024.png';

    if (!url) {
        img.src = placeholder;
        img.style.display = 'block';
    } else if (url.match(/^https?:\/\/.+\.(jpg|jpeg|png|gif|webp)$/i)) {
        img.src = url;
        img.style.display = 'block';
    } else {
        img.src = placeholder;
        img.style.display = 'block';
    }
}

function toonPopup(tekst) {
    const popup = document.getElementById("popup");
    const message = document.getElementById("popup-message");
    message.textContent = tekst;
    popup.style.display = "flex";
}

function sluitPopup() {
    document.getElementById("popup").style.display = "none";
}

