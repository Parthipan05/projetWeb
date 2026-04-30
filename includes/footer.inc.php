</main>
</div>

<a href="#" class="back-to-top" title="Retour en haut">↑</a>

<footer>
    <p class="footer-navig"><strong>Votre navigateur :</strong> <?php echo get_navigateur(); ?></p>
    <p><strong>Visites sur le site :</strong> <?= $hits ?></p>
    <p>
        <a href="sitemap.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">Plan du site</a>
        | <a href="tech.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">Page Tech</a>
		| <a href="apropos.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">À propos</a>
		| <a href="aide.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">Aide</a>
		| <a href="sources.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">Sources</a>
		| <a href="confidentialite.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">Confidentialité</a>
    </p>
    <p>
        © 2026 — PIRABAKARAN Parthipan et HANANE Sanaa — Développement Web (CY Cergy Paris Université).
    </p>
</footer>

</body>

</html>