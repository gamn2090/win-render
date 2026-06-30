<script src="https://kit.fontawesome.com/6f9ce98b2d.js" crossorigin="anonymous"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

<style>
    @font-face {
        font-family: "Poppins";
        src:
            local("Poppins"),
            url("/assets/fonts/Poppins-Regular.otf") format("OpenType"),
    }

    @font-face {
        font-family: "NeulisNeue-SemiBold";
        src:
            local("NeulisNeue-SemiBold"),
            url("/assets/fonts/NeulisNeue-SemiBold.otf") format("OpenType");
    }

    @font-face {
        font-family: "NeulisNeue-Bold";
        src:
            local("NeulisNeue-Bold"),
            url("/assets/fonts/NeulisNeue-Bold.otf") format("OpenType");
    }

    html {
        scroll-behavior: smooth;
        font-family: "Poppins", sans-serif;
        font-size: 16px;
    }

    .title {
        font-family: "NeulisNeue", sans-serif;
    }

    .font-regular {
        font-family: "Poppins", sans-serif;
    }

    .headline-small {
        font-family: "NeulisNeue-Bold", sans-serif;
        font-size: 2rem;
        line-height: 2.438rem;
    }

    .headline-large {
        font-family: "NeulisNeue-Bold", sans-serif;
        font-size: 3.438rem;
        line-height: 3.688rem;
    }

    .subheading {
        font-family: "NeulisNeue-Bold", sans-serif;
        font-size: 1.313rem;
        line-height: 2rem;
    }

    .body-copy {
        font-family: "Poppins", sans-serif;
        line-height: 2rem;
        font-size: 1.188rem;
    }

    .button-text {
        font-family: "Poppins", sans-serif;
        font-size: 1.125rem;
    }

    button {
        font-family: "Poppins", sans-serif;
        font-size: 1.125rem;
    }

    @media only screen and (min-width: 2500px) {
        .headline-large {
            font-family: "NeulisNeue-Bold", sans-serif;
            font-size: 55pt;
            line-height: 59pt;
        }
    }
    @media not all and (min-width: 768px) {
        html {
            font-size: 12px;
        }
    }
    .box {
    width:100vw;
    height: 15vh;
    }
    .border-circle {
        background: radial-gradient(120px at top,#FDF6F3 98%,#FFD3FB) 60% / calc(1*120px) 100%;
    }
    .border-circle-b {
        background: radial-gradient(120px at bottom,#FFD3FB 98%,#FDF6F3) 60% / calc(1*120px) 100%;
    }
</style>