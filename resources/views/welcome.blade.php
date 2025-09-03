<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Welcome | Tortha POS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root {
      --primary: #a869aa;
      --secondary: #4f46e5;
      --light-bg: #f9fafb;
      --text-color: #1f2937;
      --muted: #6b7280;
    }

    body {
      background-color: var(--light-bg);
      font-family: 'Segoe UI', sans-serif;
      color: var(--text-color);
    }
    .navbar {
            background: linear-gradient(to right, var(--light-bg), var(--primary));
            padding: 1rem;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 600;
            color: #fff;
        }

        .nav-link {
            color: #fff;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: black; 
        }

    .hero {
      padding: 100px 15px;
      text-align: center;
      background: linear-gradient(to right, var(--primary), var(--secondary));
      color: white;
    }

    .hero h1 {
      font-size: 2.75rem;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .hero p {
      font-size: 1.25rem;
      max-width: 700px;
      margin: auto;
      color: rgba(255, 255, 255, 0.9);
    }

    .btn-primary {
      background-color: white;
      color: var(--primary);
      border: none;
      padding: 12px 28px;
      font-size: 1.1rem;
      border-radius: 8px;
      transition: all 0.3s ease-in-out;
    }

    .btn-primary:hover {
      background-color: #e0e7ff;
      color: var(--secondary);
    }

    .btn-outline-secondary {
      color: white;
      border: 1px solid white;
      padding: 12px 28px;
      font-size: 1.1rem;
      border-radius: 8px;
      transition: all 0.3s ease-in-out;
    }

    .btn-outline-secondary:hover {
      background-color: rgba(255, 255, 255, 0.1);
      color: #f3f4f6;
    }

    .features {
      background-color: #ffffff;
    }

    .features h2 {
      font-weight: 700;
      color: var(--primary);
    }

    .lead {
      max-width: 800px;
      margin: auto;
    }

    .feature-box {
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 25px;
      background: #ffffff;
      transition: transform 0.25s ease, box-shadow 0.25s ease;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
      height: 100%;
    }

    .feature-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.06);
    }

    .feature-box h4 {
      color: var(--secondary);
      font-weight: 600;
      margin-bottom: 15px;
    }

    footer {
      font-size: 0.9rem;
    }

    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2rem;
      }

      .hero p {
        font-size: 1rem;
      }

      .btn-primary, .btn-outline-secondary {
        width: 100%;
        margin-bottom: 10px;
      }
    }
  </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg d-flex justify-content-between align-items-center">            
        <x-application-logo class="w-20 h-20 fill-current text-gray-500"/></a>

            <div class="d-flex justify-content-between align-items-center">
                <a class=" nav-link me-2" href="/dashboard">Home</a>
                <a class="nav-link" href="/login">| Login</a>
                <a class="nav-link" href="/register">| Register</a>
            </div>
    </nav>
  <!-- Hero Section -->
  <div class="container-fluid hero">
    <h1>Welcome to Tortha Universal Retail POS</h1>
    <p class="mb-5">Running multiple stores is tough. Tortha POS makes it less stressful, more delightful, and dare we say... even fun? 🧾✨</p>
      <a href="{{ route('login') }}" class="btn btn-primary me-2" title="Welcome back! Let’s do this.">Login</a>
      <a href="{{ route('register') }}" class="btn btn-primary me-2" title="Welcome back! Let’s do this.">Register</a>
    <a href="https://wa.me/254716878433?text=Hello%20Tortha%20Team,%20I%27m%20interested%20in%20your%20POS%20system." class="btn btn-outline-secondary" title="Let’s build your POS empire."><i class="fab fa-whatsapp"></i> Get Started</a>
  </div>

  <!-- Capabilities Section -->
  <section class="features py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 data-aos="fade-up">✨ What Tortha POS Can Do</h2>
        <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">
          From digital payments to delivery logistics — Tortha is more than a POS. It's your business control tower.
        </p>
      </div>

      <div class="row g-4">
        <div class="col-md-6" data-aos="fade-up">
          <div class="feature-box h-100">
            <h4>📱 STK Push Payments</h4>
            <p>Instant mobile payments via STK Push (e.g., M-Pesa) directly from the POS. Fast, secure, and effortless for your team and customers.</p>
          </div>
        </div>

        <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
          <div class="feature-box h-100">
            <h4>🏬 Centralized Store Management</h4>
            <p><strong>Manager</strong> manages stores, assigns roles, and oversees every activity in real time from a unified dashboard.</p>
          </div>
        </div>

        <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
          <div class="feature-box h-100">
            <h4>🛒 Online Orders</h4>
            <p>Let customers shop online, assign orders to local stores, and manage everything from inventory to receipts in one click.</p>
          </div>
        </div>

        <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
          <div class="feature-box h-100">
            <h4>🚚 Integrated Delivery Workflows</h4>
            <p>Connect orders with delivery partners. Assign, dispatch, and track — all from inside Tortha POS.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
<!-- Onboarding Section -->
<section class="features bg-light py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 data-aos="fade-up">🚀 Getting Started — Without the Tech Headache</h2>
      <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">
        You're not just buying software. You're starting a journey with a team that wants you to win — minus the geek speak and guesswork.      </p>
    </div>

    <div class="row g-4">
      <div class="col-md-4" data-aos="fade-up">
        <div class="feature-box h-100">
          <h4>📞 1.  Let's Talk (We're Great Listeners)</h4>
          <p>Reach out to our team. We’ll understand your business model, number of stores, cashier roles, and compliance needs — and tailor the best-fit POS configuration for you.</p>
        </div>
      </div>

      <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
        <div class="feature-box h-100">
          <h4>📝 2. Contract & Payment</h4>
          <p>Once you're aligned with our plan, we formalize it with a service agreement. A deposit secures your slot in our deployment pipeline.</p>
        </div>
      </div>

      <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
        <div class="feature-box h-100">
          <h4>🛠️ 3. POS System Setup</h4>
          <p>Custom-Fitted Like a Fine Suit, your branded POS is deployed on the cloud (e.g. <code>yourbiz.tortha.com</code>) or your local infrastructure — ready for real-world use.</p>
        </div>
      </div>

      <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
        <div class="feature-box h-100">
          <h4>🔐 4. Access & Team Training</h4>
          <p>We deliver secure login credentials and admin dashboards. Then, we train your staff — cashiers, admins, managers — to confidently operate the system.</p>
        </div>
      </div>

      <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
        <div class="feature-box h-100">
          <h4>📈 5. Ongoing Support & Scaling</h4>
          <p>You're Never Alone — We Stick Around. Want to expand, analyze performance, or integrate new workflows? We offer dedicated support, upgrades, and strategic advice whenever you need it.</p>
        </div>
      </div>
    </div>

    <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="500">
      <h5 class="fw-semibold">📩 Ready to streamline your retail operations?</h5>
      <p class="mb-3">We’re not just software vendors — think of us as your retail sidekicks. Ready when you are.</p>
      <a href="https://wa.me/254716878433?text=Hello%20Tortha%20Team,%20I%27m%20interested%20in%20your%20POS%20system." class="btn btn-success" target="_blank">
        <i class="fab fa-whatsapp"></i> Chat with Us on WhatsApp
      </a>
    </div>
  </div>
</section>
<!-- Onboarding Simulator Section -->
<section class="features py-5 bg-white" id="onboarding-simulator">
  <div class="container text-center">
    <h2 class="mb-4">🎮 Try Our Onboarding Progress Simulator</h2>
    <p class="lead text-muted mb-4">Click through to see how easy it is to get started with Tortha POS — no tech headaches, no jargon.</p>

    <p class="fw-semibold" id="onboarding-label">📍 You're here: <strong>Just Browsing</strong></p>

    <div class="progress mb-4" style="height: 25px;">
      <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
           id="simulator-bar" 
           style="width: 0%;">
        0%
      </div>
    </div>

    <button class="btn btn-outline-primary" onclick="advanceOnboarding()">Next Step</button>
  </div>
</section>

  <!-- Footer -->
  <footer class="text-center mt-5 mb-3 text-muted">
    &copy; {{ date('Y') }} Tortha Software Devco. All rights reserved.
  </footer>
<script>
  const onboardingSteps = [
    "Just Browsing",
    "📞 Let's Talk (We're Great Listeners)",
    "📝 Just the Boring Legal Stuff (We Promise It's Quick)",
    "🛠️ System setup, Custom-Fitted Like a Fine Suit",
    "🔐 Logins, Training & Fist Bumps",
    "📈 You're Never Alone — We Stick Around",
    "🎉 You're Live!"
  ];

  let currentStep = 0;

  function advanceOnboarding() {
    if (currentStep < onboardingSteps.length - 1) {
      currentStep++;
      const progress = (currentStep / (onboardingSteps.length - 1)) * 100;

      document.getElementById("onboarding-label").innerHTML =
        `📍 You're here: <strong>${onboardingSteps[currentStep]}</strong>`;
      const bar = document.getElementById("simulator-bar");
      bar.style.width = `${progress}%`;
      bar.innerHTML = `${Math.round(progress)}%`;

      if (currentStep === onboardingSteps.length - 1) {
        event.target.innerText = "Restart";
        event.target.classList.remove("btn-outline-primary");
        event.target.classList.add("btn-outline-success");
      }
    } else {
      currentStep = 0;
      document.getElementById("onboarding-label").innerHTML =
        `📍 You're here: <strong>${onboardingSteps[currentStep]}</strong>`;
      const bar = document.getElementById("simulator-bar");
      bar.style.width = `0%`;
      bar.innerHTML = `0%`;
      event.target.innerText = "Next Step";
      event.target.classList.remove("btn-outline-success");
      event.target.classList.add("btn-outline-primary");
    }
  }
</script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
