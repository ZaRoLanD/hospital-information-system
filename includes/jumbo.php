<?php

class jumbo
{
    /**
     * Bandeau d'en-tête de page moderne.
     * $detail peut contenir du HTML sûr (ex. un lien), les titres sont échappés.
     */
    function getJumbo($title, $detail)
    {
        return '
            <div class="page-head">
                <h1 class="page-head-title">' . htmlspecialchars($title) . '</h1>
                <p class="page-head-sub">' . $detail . '</p>
            </div>
            ';
    }
}
