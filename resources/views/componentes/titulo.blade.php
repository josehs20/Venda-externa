<style>
    #titlePage {
        z-index: 999;
        opacity: 0.7;
        color: rgb(255, 255, 255);
        margin-top: -1%;
        margin-left: 1%;
        font-size: 16px;
    }

    @media (max-width: 700px) {
        #titlePage {
            margin-top: 10%;
            color: rgb(255, 255, 255);
            font-size: 14px;
        }

    }

    @media (max-width: 800px) {
        #titlePage {
            color: rgb(255, 255, 255);
            font-size: 14px;
        }

    }

</style>

<a id="titlePage" style="position:fixed;">{{ $titlePage }}</a>
