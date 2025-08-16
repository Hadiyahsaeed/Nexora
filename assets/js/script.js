document.addEventListener("DOMContentLoaded", () => {
    const items = document.querySelectorAll('.nav-item');
    items.forEach(item => {
        item.addEventListener('mouseover', () => {
            item.style.transform = 'scale(1.05)';
            item.style.boxShadow = '0 4px 8px rgba(0,0,0,0.2)';
        });
        item.addEventListener('mouseout', () => {
            item.style.transform = 'scale(1)';
            item.style.boxShadow = 'none';
        });
    });
});
