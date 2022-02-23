<style>
    #titlePage {
        z-index: 999;
        opacity: 0.7;
        color: orange;
        margin-top: -1%;
        margin-left: 1%;
        font-size: 20px;
    }

    @media (max-width: 700px) {
        #titlePage {
            margin-top: 10%;
            color: orange;
            font-size: 15px;
        }

    }

    @media (max-width: 800px) {
        #titlePage {
            color: orange;
            font-size: 15px;
        }

    }

</style>

<a id="titlePage" style="position:fixed;">{{ $titlePage }}</a>
