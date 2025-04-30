/**
 * Fichier JavaScript personnalisé pour la vue annuelle
 * À enregistrer dans : public/js/calendar-year-view.js
 */
document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour initialiser la vue annuelle
    window.initYearView = function(calendar) {
        // Vérifier si nous sommes en vue annuelle
        if (calendar.view.type === 'dayGridYear') {
            // Compresser la vue pour l'affichage annuel
            const yearView = document.querySelector('.fc-dayGridYear-view');
            if (yearView) {
                // Réduire la hauteur des cellules
                const dayCells = yearView.querySelectorAll('.fc-daygrid-day');
                dayCells.forEach(cell => {
                    cell.style.height = '20px';
                });
                
                // Limiter le nombre d'événements visibles par jour
                const eventContainers = yearView.querySelectorAll('.fc-daygrid-day-events');
                eventContainers.forEach(container => {
                    const events = container.querySelectorAll('.fc-event');
                    
                    // Masquer les événements au-delà du premier
                    if (events.length > 1) {
                        // Garder le premier événement visible
                        for (let i = 1; i < events.length; i++) {
                            events[i].style.display = 'none';
                        }
                        
                        // Ajouter un indicateur "+X autres" si nécessaire
                        if (events.length > 1) {
                            const moreIndicator = document.createElement('div');
                            moreIndicator.className = 'fc-more-event';
                            moreIndicator.textContent = `+${events.length - 1}`;
                            moreIndicator.style.fontSize = '7px';
                            moreIndicator.style.textAlign = 'center';
                            moreIndicator.style.color = '#666';
                            container.appendChild(moreIndicator);
                        }
                    }
                });
            }
        }
    };
});