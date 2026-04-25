/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
// Empêche le navigateur de gérer le scroll automatiquement
if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
}

// Sauvegarde le scroll AVANT navigation
document.addEventListener('click', (event) => {
    const link = event.target.closest('a');

    if (!link) return;

    // Ignore liens externes / nouveaux onglets / ancres
    if (link.target || link.hasAttribute('download')) return;
    if (link.origin !== location.origin) return;
    if (link.hash && link.pathname === location.pathname) return;

    sessionStorage.setItem('scrollY', String(window.scrollY));
});

// Restaure le scroll APRÈS navigation
window.addEventListener('pageshow', () => {
    const y = sessionStorage.getItem('scrollY');

    if (y !== null) {
        requestAnimationFrame(() => {
            window.scrollTo(0, Number(y));
            sessionStorage.removeItem('scrollY');
        });
    }
});
