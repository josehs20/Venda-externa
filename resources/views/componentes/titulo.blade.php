<style>
    #titlePage {
        z-index: 999;
        opacity: 0.7;
        color: rgb(255, 255, 255);
       
        margin-left: 1%;
        font-size: 16px;
    }

  

    @media (max-width:937px) {
        #titlePage {
            margin-top: 13%;
            color: rgb(255, 255, 255);
            font-size: 12px;
        }

    }
    @media screen and (min-width: 500px) and (max-width: 930px) {
        #titlePage {
         margin-top: 5%;
            color: rgb(255, 255, 255);
            font-size: 18px;
        }

    }

</style>

<a id="titlePage" style="position:fixed;">{{ $titlePage }}</a>
