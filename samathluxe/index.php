<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Samath Luxe</title>
    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="./scripts/script.js"></script>
</head>
<body>
    <nav class="navbar" id="navbar">
        <a href="#inicio" class="logo">SAMATH LUXE</a>
        <ul class="nav-links">
            <li><a href="#inicio" class="home-btn">INICIO</a></li>
            <li><a href="#productos">PRODUCTOS</a></li>
            <li><a href="#nosotros">NOSOTROS</a></li>
            <li><a href="#contacto">CONTACTO</a></li>
        </ul>
    </nav>
    
    <!-- Hero Section -->
    <section id="inicio" class="hero">
        <div class="hero-content">
            <div class="hero-subtitle">COLECCIÓN</div>
            <h1 class="hero-title">REFLEJA TU ESENCIA</h1>
            <div class="hero-buttons">
                <a href="#productos" class="btn-primary">EXPLORAR COLECCIÓN</a>
                <a href="#contacto" class="btn-secondary">CONTACTO</a>
            </div>
        </div>
    </section>

    <!-- Section Products Preview -->
    <section id="productos" class="content-section">
        <div class="section-header">
            <h2 class="section-title">PRODUCTOS DESTACADOS</h2>
            <p class="section-description"> </p>
        </div>
    </section>

    <section class="products-preview">
        <div class="products-content">
            <div class="product-category">
                <h3 class="product-category-title">ANILLOS</h3>
<br>
                <div class="product-item">
                    <div class="product-item-header">
                        <span class="product-item-name">Anillo Ansiedad Océano</span>
                        <span class="product-item-price">$29.000</span>
                    </div>
                    <p class="product-item-description">Elegante anillo ajustable con diseño de tortuga marina y una estrella en cristales brillantes y detalle de piedra azul central. Perfecto para looks casuales y ocasiones especiales.</p>
                </div>
<br>
                <div class="product-item">
                    <div class="product-item-header">
                        <span class="product-item-name">Anillo Nympha Dorado</span>
                        <span class="product-item-price">$20.000</span>
                    </div>
                    <p class="product-item-description">Delicado anillo con múltiples cristales que simulan una mariposa. Ideal para combinar con otros anillos y crear un look único y moderno.</p>
                </div>

            </div>

            <div class="product-category">
                <h3 class="product-category-title">CADENAS</h3>
                
                <div class="product-item">
                    <div class="product-item-header">
                        <span class="product-item-name">Cadena Colibri Verde</span>
                        <span class="product-item-price">$29.000</span>
                    </div>
                    <p class="product-item-description">Cadena de cierre en forma de corazón con detalles de piedras azules y verdes. Ideal para combinar con otras cadenas o anillos.</p>
                </div>

                <div class="product-item">
                    <div class="product-item-header">
                            <span class="product-item-name">Cadena Manos Corazón</span>
                        <span class="product-item-price">$27.000</span>
                    </div>
                    <p class="product-item-description">Collar minimalista de corazón entrelazado por 2 manos en acabado dorado. Simboliza amor eterno y conexión profunda. Ideal como regalo especial.</p>
                </div>
            </div>

            <a href="#productos" style="text-decoration: underline; color: #333; font-size: 14px;" onclick="event.preventDefault(); document.getElementById('productos').scrollIntoView({behavior: 'smooth'});">Ver más</a>
        </div>
        
        <div class="products-images">
            <div class="product-image"></div>
            <div class="product-image"></div>
            <div class="product-image"></div>
            <div class="product-image"></div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="nosotros" class="about-section">
        <div class="about-image">

        </div>
        <div class="about-content">
            <div class="about-info">
                <h2 class="about-name">SOBRE NOSOTROS</h2>
                <div class="about-subtitle"><p>NUESTRA MISIÓN</p></div>
                <p class="about-description">En Samath Luxe, comercializamos joyería y accesorios con los más altos estándares de calidad, buscando realzar la belleza femenina y destacar la autenticidad de cada persona. Nos comprometemos a ofrecer piezas cuidadosamente seleccionadas y diseñadas para reflejar elegancia, identidad y expresión personal.</p>
                <div class="about-subtitle" style="margin-top: 40px;">NUESTRA VISIÓN</div>
                <p class="about-description">Aspiramos ser una marca reconocida por empoderar a las mujeres a través de joyas y accesorios que combinan estilo, autenticidad y calidad. Aspiramos a inspirar confianza y originalidad, consolidándonos como un referente en el mercado por nuestras propuestas innovadoras y nuestro compromiso con la excelencia.</p>
            </div>
        </div>
    </section>

    <!-- Quality Section -->
    <section class="content-section">
        <div class="section-header">
            <div class="section-subtitle">CALIDAD</div>
            <h2 class="section-title">MATERIALES PREMIUM</h2>
            <p class="section-description">
                En Samath Luxe trabajamos con una cuidadosa selección de materiales de alta calidad para garantizar piezas duraderas y elegantes. Nuestras joyas están elaboradas con <strong>acero inoxidable</strong>, <strong>cover gold</strong>, <strong>plata 925</strong> y <strong>rodio</strong>, materiales reconocidos por su resistencia y brillo duradero. 
                <br><br>
                También incorporamos <strong>cobre</strong>, <strong>aluminio</strong> y <strong>latón</strong> en diseños específicos, combinando tradición y modernidad para crear piezas únicas que resisten el paso del tiempo. Cada material es seleccionado por sus propiedades específicas: resistencia a la oxidación, hipoalergénico y mantenimiento del color original, asegurando que tu inversión en belleza perdure.
            </p>
        </div>
    </section>

   

    <!-- Custom Section -->
    <section class="custom-section">
        <div class="custom-content">
            <div class="section-subtitle">PERSONALIZACIÓN</div>
            <h2 class="custom-title">DISEÑOS ÚNICOS</h2>
            <p class="custom-description">¿Buscas una pieza especial que exprese tu estilo? Contamos con una cuidada selección de joyas únicas para cada ocasión y personalidad.</p>
            <a href="#contacto" class="btn-primary">SOLICITAR COTIZACIÓN</a>
            <p class="custom-note">Te contactaremos en menos de 24 horas para conocer tu idea.</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contacto" class="contact-section">
        <div class="contact-container-inline">
            <div class="contact-header-inline">
                <div class="section-subtitle">PONTE EN</div>
                <h2 class="section-title">CONTACTO</h2>
                <p class="section-description">
                    Estamos aquí para ayudarte a encontrar la pieza perfecta que refleje tu esencia única. 
                    Contáctanos y descubre cómo podemos hacer realidad tu visión.
                </p>
            </div>

            <div class="contact-info-grid">
                <div class="contact-info-box">
                    <h3 class="info-title">Información de Contacto</h3>
                    <div class="info-item">
                        <div class="info-icon-inline"><i class="bi bi-envelope-fill"></i></div>
                        <div class="info-text">
                            <a href="mailto:info@samathluxe.com">info@samathluxe.com</a>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon-inline"><i class="bi bi-telephone-fill"></i></div>
                        <div class="info-text">
                            <a href="tel:+573239604096">+57 323 9604096</a>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon-inline"><i class="bi bi-clock-fill"></i></div>
                        <div class="info-text">
                            Lun - Vie: 9:00 AM - 6:00 PM<br>
                            Sáb: 10:00 AM - 4:00 PM
                        </div>
                    </div>
                </div>

                <div class="contact-info-box">
                    <h3 class="info-title">Ubicación</h3>
                    <div class="info-item">
                        <div class="info-icon-inline"><i class="bi bi-geo-alt-fill"></i></div>
                        <div class="info-text">
                            Bogotá, Colombia<br>
                            Las Ferias<br>
                            Carrera 13 #85-32, Local 201
                        </div>
                    </div>
                </div>

                <div class="contact-info-box">
                    <h3 class="info-title">Atención Personalizada</h3>
                    <div class="info-text">
                        <p>Ofrecemos citas personalizadas para asesoramiento en la selección de joyas y diseños únicos. Agenda tu cita con 24 horas de anticipación.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <!-- Logo -->
            <div class="footer-brand">
                <div class="logo">SAMATH LUXE</div>
            </div>

            <!-- Enlaces -->
            <nav>
                <ul class="footer-links">
                    <li><a href="#inicio">INICIO</a></li>
                    <li><a href="#productos">PRODUCTOS</a></li>
                    <li><a href="#nosotros">NOSOTROS</a></li>
                    <li><a href="#contacto">CONTACTO</a></li>
                    <li><a href="../index.php?action=login" style="color: #d4af37; font-weight: bold;">ADMINISTRACIÓN</a></li>
                </ul>
            </nav>

            <!-- Contacto -->
            <div class="footer-contact">
                <p>info@samathluxe.com</p>
                <p>&copy; 2024 Samath Luxe</p>
            </div>
        </div>
    </footer>

</body>
</html>