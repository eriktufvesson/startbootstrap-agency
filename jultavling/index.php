<?php
$month = array_key_exists('m', $_GET) ? $_GET['m'] : date('M');
$day = array_key_exists('d', $_GET) ? $_GET['d'] : date('d');

if ($month !== 'Dec' || $day > 28) {
	header('Location: /');
	exit();
}
// 1 = 1-7, 2 = 8-14, 3 = 15-21, 4 = 22-28
// 1 = liten
// 2 = tillsammans
// 3 = mellan
// 4 = stor
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="title" content="Dress by heart – Personlig stil och coachning">
    <meta name="description" content="Jag hjälper dig att må bra i dina kläder, utstråla vem du är och hitta glädjen i att bygga en garderob som passar ditt liv. Jag ger dig inga färdiga regler utan jag ställer frågor så att vi tillsammans kan utforska din personliga stil. Jag erbjuder inspirationsföreläsningar, workshops och individuell coachning. Allt kan skräddarsys efter dina behov.">
    <meta name="keywords" content="Stilcoach, Personlig stil, Coaching, Inspiration, Workshop, Personlig coachning">
    <meta name="author" content="">
    <title>Jultävling | Dress by heart</title>
    <!-- Custom Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display+SC" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
    <!-- Theme CSS -->
    <link href="../css/agency.min.css?bust=20171129" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css"
    />
    <!-- Favicon -->
    <link rel="icon" href="../favicon-heart.ico">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js" integrity="sha384-0s5Pv64cNZJieYFkXYOTId2HMA2Lfb6q2nAcx2n0RTLUnCAoTTsS0nKEO27XyKcY" crossorigin="anonymous"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js" integrity="sha384-ZoaMbDF+4LeFxg6WdScQ9nnR1QC2MIRxA1O9KWEXQwns1G8UNyIEZIQidzb0T1fo" crossorigin="anonymous"></script>
    <![endif]-->
</head>
<body id="page-top" class="jultavling">
    <!-- Navigation -->
    <nav id="mainNav" class="navbar navbar-default navbar-custom navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="/">
                    <i class="fa fa-heart"></i>
                    <div class="brand-wrapper white">
                        <span class="brand">
                            Dress by heart
                        </span>
                        <span class="byline">
                            Personlig stil och coachning
                        </span>
                    </div>
                </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="/#services">Mina tjänster</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="/#suggestions">Skräddarsytt</a>
                    </li>
                    <li class="active">
                        <a class="page-scroll" href="/boka">Boka</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="/#about">Om mig</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="/#contact">Kontakt</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    <?php
      if ($day >= 1 && $day < 8) {
	?>
    <!-- Header -->
    <header class="garderoben" style="background: linear-gradient( rgba(0, 0, 0, 0.05), rgba(0, 0, 0, 0.65) ), url('../img/header-bg-jultavling-1.jpg')">
        <div class="container">
            <div class="intro-text">
                <div class="intro-heading">
                    <h1>Jultävling - 1:a advent</h1>
                </div>
                <div class="intro-slogan"></div>
                <div class="intro-lead-in">
                    <p class="large question">
                        Vilken skillnad skulle en stilcoachning göra för dig och ditt liv? 
                    </p>
                    <p class="large">
                        Vinn två timmar stilcoacning! Svara på frågan för att få en chans att bli min referenskund.
                    </p>
                </div>
            </div>
        </div>
    </header>
    <?php
}
else if ($day >= 8 && $day < 15) {
?>
    <header class="garderoben" style="background: linear-gradient( rgba(0, 0, 0, 0.05), rgba(0, 0, 0, 0.65) ), url('../img/header-bg-jultavling-2.jpg')">
        <div class="container">
            <div class="intro-text">
                <div class="intro-heading">
                    <h1>Jultävling - 2:a advent</h1>
                </div>
                <div class="intro-slogan"></div>
                <div class="intro-lead-in">
                    <p class="large question">
                        Vilken fördel ser du med att göra en stilcoachning tillsammans?
                    </p>
                    <p class="large">
                        Vinn stilcoachning för två personer i tre timmar! Svara på frågan för att få en chans att bli mina referenskunder.
                    </p>
                </div>
            </div>
        </div>
    </header>
    <?php
}
else if ($day >= 15 && $day < 22) {
?>
    <header class="garderoben" style="background: linear-gradient( rgba(0, 0, 0, 0.05), rgba(0, 0, 0, 0.65) ), url('../img/header-bg-jultavling-3.jpg')">
        <div class="container">
            <div class="intro-text">
                <div class="intro-heading">
                    <h1>Jultävling - 3:e advent</h1>
                </div>
                <div class="intro-slogan"></div>
                <div class="intro-lead-in">
                    <p class="large question">
                        Varför vill du kombinera stilcoachning med persnal shopper?
                    </p>
                    <p class="large">
                        Vinn två timmar stilcoacning följt av två timmar med personal shopper! Svara på frågan för att få en chans att bli min referenskund.
                    </p>
                </div>
            </div>
        </div>
    </header>
    <?php
}
else if ($day >= 22 && $day < 29) {
?>
    <header class="garderoben" style="background: linear-gradient( rgba(0, 0, 0, 0.05), rgba(0, 0, 0, 0.65) ), url('../img/header-bg-jultavling-4.jpg')">
        <div class="container">
            <div class="intro-text">
                <div class="intro-heading">
                    <h1>jultävling - 4:e advent</h1>
                </div>
                <div class="intro-slogan">
                
                </div>
                <div class="intro-lead-in">
                    <p class="large question">
                        Varför vill du göra en stilcoachning och få hjälp från att rensa till att komplettera din garderob?
                    </p>
                    <p class="large">
                        Vinn stilcoachning med garderobsgenomgång och personal shopper (2+2+2 timmar)! Svara på frågan för att få en chans att bli min referenskund.
                    </p>
                </div>
            </div>
        </div>
    </header>
    <?php
}
?>
    <section class="bg-light-gray">
      <div class="container">
        <form name="competition" id="competitionForm">
          <div class="row">
            <div class="col-md-12 text-center">
              <h2>Skicka in ditt tävlingssvar</h2>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                  <input type="text" name="Namn" class="form-control input-xl" placeholder="Namn *" id="name" required data-validation-required-message="Vänligen ange ditt namn.">
                  <p class="help-block text-danger"></p>
              </div>
            </div>  
            <div class="col-md-12">
                <div class="form-group">
                    <input type="email" name="E-post" class="form-control input-xl" placeholder="Din e-postadress *" id="email" required data-validation-required-message="Please enter your email address.">
                    <p class="help-block text-danger"></p>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <textarea class="form-control input-xl" name="Svar" placeholder="Tävlingssvar *" id="answer" required data-validation-required-message=""></textarea>
                    <p class="help-block text-danger"></p>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12 text-center">
                <button type="submit" class="btn btn-xl-success">
                    Skicka
                </button>
            </div>
          </div>
          <div class="row">
              <div class="col-md-12">
                  <div id="success"></div>
              </div>
          </div>
          <p class="large">
              Tävlingen gäller möjligheten att bli min referenskund. Detta innebär att om du vinner får du stilcoachningen gratis och du godkänner 
              att jag dokumenterar och delar med mig av din väg mot din personliga stil. 
              Stilcoachningen kan ske i Dress by hearts lokaler i Lund, hos dig eller möte online. Garderobsgenomgång sker hemma hos dig och shopping 
              sker i Lund eller Malmö. Körersättning inom Skåne ingår för garderobsgenomgången. Om du vinner bokar vi och genomför stilcoachningen innan 
              den sista februari. När du deltar i tävlingen godkänner du att jag får använda ditt tävlingssvar för att formulera framtida marknadsföringsmaterial 
              som då självklart kommer vara helt anonymiserat. Tävlingen pågår från fredag till midnatt efterförljande torsdag. 
              Vinnaren utses av Jane Tufvesson, baserat på tävlingssvaren. Vinnaren meddelas i nästföljande nyhetsbrev samt på 
              Dress by hearts Instagram, Facebook och i Facebook-gruppen Må bra i dina kläder. 
          </p>
        </form>
      </div>
    </section>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h3>
                        <i class="fa fa-heart"></i> Dress by heart
                    </h3>
                    <h5>&ndash; Din stil från hjärtat</h5>
                </div>
                <div class="col-md-4">
                    <p>
                        <strong>Dress by heart</strong><br /> c/o Jane Tufvesson</br>
                        Skånska gränden 5<br /> 226 39 Lund<br />
                    </p>
                </div>
                <div class="col-md-4">
                    <p>
                        <strong>Telefon &amp; e-post</strong><br>
                        <a href="tel:0705482628">070 548 26 28</a><br />
                        <a href="mailto:info@dressbyheart.se">info@dressbyheart.se</a><br />
                    </p>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <span class="copyright">&copy; Dress by heart 2017</span>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline social-buttons">
                        <li><a href="https://www.instagram.com/dressbyheart.se" target="_blank"><i class="fa fa-instagram"></i></a>
                        </li>
                        <li><a href="https://www.facebook.com/dressbyheart.se" target="_blank"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li><a href="https://www.linkedin.com/company/dressbyheart" target="_blank"><i class="fa fa-linkedin"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline quicklinks">
                        <li><a href="cookies.html" target="_blank">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <!-- jQuery -->
    <script src="../vendor/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap-sass/assets/javascripts/bootstrap.min.js"></script>
    <!-- Plugin JavaScript -->
    <script src="../vendor/jquery.easing/js/jquery.easing.min.js"></script>
    <!-- Contact Form JavaScript -->
    <script src="../js/jqBootstrapValidation.min.js"></script>
    <script src="../js/contact_me.min.js"></script>
    <!-- Page specific script -->
    <script src="../js/jultavling.min.js"></script>
    <!-- Theme JavaScript -->
    <script src="../js/agency.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
    <script>
        window.addEventListener("load", function () {
            window.cookieconsent.initialise({
                "palette": {
                    "popup": {
                        "background": "#254E70"
                    },
                    "button": {
                        "background": "transparent",
                        "text": "#fff",
                        "border": "#37718E"
                    }
                },
                "content": {
                    "message": "Denna webbplats använder cookies för att ge dig den bästa möjliga upplevelsen. ",
                    "dismiss": "Jag förstår!",
                    "link": "Mer info",
                    "href": "/cookies.html"
                }
            })
        });
    </script>
    <!-- Google Analytics -->
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
        ga('create', 'UA-101011276-1', 'auto');
        ga('send', 'pageview');
    </script>
</body>
</html>