document.addEventListener('mousemove', function(e) {
    const flashlight = document.getElementById('flashlight');
    const x = e.clientX;
    const y = e.clientY;
    flashlight.style.left = `${x}px`;
    flashlight.style.top = `${y}px`;
});
