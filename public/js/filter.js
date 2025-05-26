function filterGames(games) {
    // JavaScript om de filterfunctie te implementeren
    document.getElementById('zoekfunctie').addEventListener('input', function() {
                        const filterValue = this.value.toLowerCase();
                        const gameCards = document.querySelectorAll('.game-card');
                        gameCards.forEach(card => {
                            const gameName = card.querySelector('h3').textContent.toLowerCase();
                            if (gameName.includes(filterValue)) {
                                card.style.display = '';
                            } else {
                                card.style.display = 'none';
                            }
                        });
                    });
                }