const quotes = [
  "Lo sport non forma solo atleti: forma persone consapevoli, solidali e capaci di costruire un futuro migliore.",
  "Ogni squadra locale puo diventare una casa per chi cerca amicizia, fiducia e nuove opportunita.",
  "Inclusione significa dare spazio a tutti, trasformando le differenze in una forza condivisa.",
  "Rispettare compagni, avversari e regole e il primo allenamento per diventare cittadini migliori."
];

const quoteText = document.getElementById("quote-text");
const quoteButton = document.getElementById("quote-button");
const sectionsToReveal = document.querySelectorAll(".panel, .impact-card, .quote-box, .cta-box");

let quoteIndex = 0;

if (quoteButton && quoteText) {
  quoteButton.addEventListener("click", () => {
    quoteIndex = (quoteIndex + 1) % quotes.length;
    quoteText.textContent = `"${quotes[quoteIndex]}"`;
  });
}

const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("reveal", "is-visible");
        observer.unobserve(entry.target);
      }
    });
  },
  { threshold: 0.18 }
);

sectionsToReveal.forEach((element) => {
  element.classList.add("reveal");
  observer.observe(element);
});
