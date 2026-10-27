<?php

include_once 'config.php';

session_start();

error_reporting(0);


// per me shiku nese osht i kyqun
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// me shiku nese perdoruesi nuk eshte 'admin' (perdoruesi), nese nuk eshte me hjek (pasi kjo eshte faqja e administratorave)
if ($_SESSION['username'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE HTML>

<html>

<head>
    <meta name="author" content="Elion Shala">
    <link rel="stylesheet" type="text/css" href="css/ballina.css">
    <title>VentoTravel - Administrator</title>
</head>
    
<body style="background-image: url(img/bck.jpg)">
    <header>
        <h1>VentoTravel</h1>
        <h3>Miresevjen, <?php echo $_SESSION['username']; ?>!</h3>
    </header>

    <nav>
        <ul>
            <li><a href="admin.php" class="active">Admin</a></li>
            <li><a href="admin-destinacionet.php">Menaxho Dest.</a></li>
            <li><a href="admin-rezervimet.php">Menaxho Rez.</a></li>
            <li><a href="admin-feedbacks.php">Menaxho Feedbacks</a></li>
            <li><a href="admin-faq.php">Menaxho FAQ</a></li>
            <li><a href="logout.php">Dil</a></li>
        </ul>
    </nav>
    
    <div class="text">
        <p>VentoTravel është një agjensi udhëtimesh me një histori të pasur dhe një vizion të fuqishëm për të ofruar eksperiencat më të mahnitshme të udhëtimit. Me një angazhim të palëkundur ndaj shërbimit të klientit dhe një ekip të pasionuar të specialistëve të udhëtimit, VentoTravel ka arritur të bëhet një referencë e njohur në botën e udhëtimeve dhe turizmit.</p>
    </div>
    <div class="text">
        <p>Fokusohemi në ofrimin e një spektri të gjerë të shërbimeve të udhëtimit, duke përfshirë paketa të personalizuara, pushime në destinacione eksotike, udhëtime të organizuara, dhe shërbime të tjera të lidhura me udhëtimet. Me një rrjet të gjërë partnerësh dhe bashkëpunëtorësh, ne mundësojmë klientëve tanë të shijojnë udhëtimet e tyre pa stres dhe mundime.</p>
        <p>Ne po zhvillojmë gjithmonë dhe po shtojmë vlerë në platformën tonë. Mund të besoni në angazhimin tonë për përmirësimin e çdo aspekti të faqes sonë dhe për të siguruar një përvojë të jashtëzakonshme për ju. Ju ftojmë të na ndiqni për të mbetur në dijeni të çdo zhvillimi dhe inovacioni të ardhshëm</p>
        <p>Një nga elementët kryesorë të identitetit tonë është përqëndrimi në kualitetin dhe sigurinë. Ne kemi një ekip të specializuar në kontrollin e cilësisë dhe sigurisë së çdo udhëtimi që ofrojmë. Kujdesi ndaj detajeve dhe përpikmëria në planifikim është ajo që bën udhëtimet me VentoTravel të njëjta me aventura të paharrueshme.</p>
    </div>
    
    <div class="text">
        <p>VentoTravel është e përkushtuar për të ofruar jo vetëm udhëtime, por edhe një eksperiencë të plotë udhëtimi që tejkalon pritshmëritë. Për ne, çdo udhëtim është një ngjarje unike dhe e pazëvendësueshme, dhe ne punojmë për të bërë secilin moment të paharrueshëm për klientët tanë.</p>
        <p>Në qendër të misionit tonë është respekti ndaj kulturave dhe mjediseve të cilat vizitojmë. Ne inkurajojmë udhëmarrjen e të gjithë klientëve tanë për të eksploruar, për të mësuar nga përvojat e reja, dhe për të ndarë momentet e bukura me bashkëudhëtarët e tyre.</p>
        <p>Me një vizion të gjatë të përparimit dhe diversifikimit të shërbimeve tona, VentoTravel është në një rrugë të qëndrueshme drejt ardhmërisë së suksesshme. Ndihemi krenarë për arritjet tona deri më tani dhe entuziaste për udhëtimet e reja dhe aventurat që na presin në të ardhmen. Bashkohuni me ne në këtë rrugë të emocionuar të udhëtimit dhe zbuloni botën me VentoTravel!</p>
    </div>
</body>
    
</html>