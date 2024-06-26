// script.js

document.addEventListener("DOMContentLoaded", function() {
    const canvas = document.getElementById('donutCanvas');
    const ctx = canvas.getContext('2d');

    // Set canvas size
    canvas.width = 820;
    canvas.height = 250;

    let A = 0;
    let B = 0;

    function renderFrame() {
        ctx.fillStyle = 'white';
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        const z = new Array(1760).fill(0);
        const b = new Array(1760).fill(' ');

        for (let j = 0; j < 6.28; j += 0.07) {
            for (let i = 0; i < 6.28; i += 0.02) {
                const c = Math.sin(i);
                const d = Math.cos(j);
                const e = Math.sin(A);
                const f = Math.sin(j);
                const g = Math.cos(A);
                const h = d + 2;
                const D = 1 / (c * h * e + f * g + 5);
                const l = Math.cos(i);
                const m = Math.cos(B);
                const n = Math.sin(B);
                const t = c * h * g - f * e;
                const x = Math.floor(40 + 30 * D * (l * h * m - t * n));
                const y = Math.floor(12 + 15 * D * (l * h * n + t * m));
                const o = x + 80 * y;
                const N = Math.floor(8 * ((f * e - c * d * g) * m - c * d * e - f * g - l * d * n));
                if (22 > y && y > 0 && x > 0 && 80 > x && D > z[o]) {
                    z[o] = D;
                    b[o] = ".,-~:;=!*#$@"[N > 0 ? N : 0];
                }
            }
        }

        ctx.font = '16px monospace';
        ctx.fillText('whoa!! a donut', 350, 10); // Adjust text position as needed

        for (let k = 0; k < 1760; k++) {
            if (k % 80 === 0) {
                ctx.fillText(b.slice(k, k + 80).join(''), 50, 20 + 10 * (k / 80));
            }
        }

        A += 0.04;
        B += 0.02;

        requestAnimationFrame(renderFrame);
    }

    // Initially hide the loader
    const loader = document.getElementById('loader');
    loader.style.display = 'none';

    // Event listener for the Enter button click
    const enterButton = document.getElementById('enterButton');
    enterButton.addEventListener('click', function() {
        // Hide main content
        const mainContent = document.querySelector('main');
        mainContent.style.display = 'none';
        
        // Show loader
        loader.style.display = 'block';

        // Start rendering the donut animation
        
        renderFrame();
        

        // Simulate loading time (adjust as needed)
        setTimeout(function() {
            // Redirect to the desired page after loading completes
            window.location.href = '../axl.com/pages/login.html'; // Replace with your desired page URL
        }, 3000); // Adjust the delay (in milliseconds) as needed
    });
});
