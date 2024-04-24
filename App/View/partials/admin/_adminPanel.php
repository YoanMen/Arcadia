<?php
// aside menu for admin page
use App\Core\Security;
?>
<aside class="dashboard-panel">
  <a class="dashboard__logo" href="/">

    <svg viewBox="0 0 174.06268 103.78361" version="1.1" id="svg1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg">
      <defs id="defs1" />
      <g id="layer1" transform="translate(758.29482,-240.39268)">
        <path style="font-weight:bold;font-size:80.4345px;font-family:Kodchasan;-inkscape-font-specification:'Kodchasan Bold';fill:#f99d26;fill-opacity:1;stroke:#fb9e27;stroke-width:7.917;stroke-dasharray:none;stroke-opacity:1" d="m -743.23631,300.65533 q -5.1478,0 -8.12388,-2.49347 -2.97608,-2.49346 -2.97608,-6.59562 0,-3.05652 1.20652,-5.30868 1.20652,-2.3326 4.6652,-4.90651 l 31.61076,-23.88904 q 1.04565,-0.80435 1.44782,-1.44782 0.48261,-0.64348 0.48261,-1.52826 0,-1.84999 -2.81521,-1.84999 h -29.43903 q -2.3326,0 -4.02172,-1.04565 -1.60869,-1.12608 -1.60869,-3.05651 0,-2.01087 1.60869,-3.05651 1.60869,-1.12609 4.02172,-1.12609 h 31.9325 q 5.22824,0 8.12388,2.41304 2.97608,2.3326 2.97608,6.67606 0,3.29782 -1.20652,5.46955 -1.20651,2.17173 -4.6652,4.74563 l -31.61076,23.88905 q -1.04565,0.80435 -1.52825,1.52826 -0.40217,0.64347 -0.40217,1.60869 0,1.68912 3.78042,1.68912 h 30.64554 q 2.3326,0 3.94129,1.12608 1.68913,1.04565 1.68913,2.97608 0,2.01086 -1.60869,3.13695 -1.60869,1.04564 -4.02173,1.04564 z m 66.68012,0.80435 q -6.9978,0 -12.46735,-2.49347 -5.38911,-2.5739 -8.44562,-7.23911 -3.05651,-4.6652 -3.05651,-10.77822 0,-6.11302 3.05651,-10.77822 3.05651,-4.6652 8.44562,-7.15867 5.46955,-2.57391 12.46735,-2.57391 6.9978,0 12.38691,2.57391 5.46955,2.49347 8.52606,7.15867 3.05651,4.6652 3.05651,10.77822 0,6.11302 -3.05651,10.77822 -3.05651,4.66521 -8.52606,7.23911 -5.38911,2.49347 -12.38691,2.49347 z m 0,-8.36519 q 6.19346,0 9.73257,-3.21738 3.61956,-3.21738 3.61956,-8.92823 0,-5.71085 -3.61956,-8.92823 -3.53911,-3.21738 -9.73257,-3.21738 -6.19346,0 -9.81301,3.21738 -3.53912,3.21738 -3.53912,8.92823 0,5.71085 3.53912,8.92823 3.61955,3.21738 9.81301,3.21738 z m 51.7192,8.36519 q -6.9978,0 -12.46734,-2.49347 -5.38912,-2.5739 -8.44563,-7.23911 -3.05651,-4.6652 -3.05651,-10.77822 0,-6.11302 3.05651,-10.77822 3.05651,-4.6652 8.44563,-7.15867 5.46954,-2.57391 12.46734,-2.57391 6.9978,0 12.38692,2.57391 5.46954,2.49347 8.52605,7.15867 3.05651,4.6652 3.05651,10.77822 0,6.11302 -3.05651,10.77822 -3.05651,4.66521 -8.52605,7.23911 -5.38912,2.49347 -12.38692,2.49347 z m 0,-8.36519 q 6.19346,0 9.73258,-3.21738 3.61955,-3.21738 3.61955,-8.92823 0,-5.71085 -3.61955,-8.92823 -3.53912,-3.21738 -9.73258,-3.21738 -6.19345,0 -9.81301,3.21738 -3.53911,3.21738 -3.53911,8.92823 0,5.71085 3.53911,8.92823 3.61956,3.21738 9.81301,3.21738 z" id="text1-5" aria-label="Zoo" />
        <path d="m -754.90186,343.94335 c -1.016,0 -1.8288,-0.27093 -2.4384,-0.8128 -0.6096,-0.54187 -0.9144,-1.28693 -0.9144,-2.2352 v -17.78 c 0,-4.91067 1.40547,-8.75453 4.2164,-11.5316 2.81093,-2.77707 6.72253,-4.1656 11.7348,-4.1656 3.28507,0 6.12987,0.62653 8.5344,1.8796 2.40453,1.25307 4.23333,3.06493 5.4864,5.4356 1.28693,2.37067 1.9304,5.16467 1.9304,8.382 v 17.78 c 0,0.94827 -0.3048,1.69333 -0.9144,2.2352 -0.6096,0.54187 -1.4224,0.8128 -2.4384,0.8128 -1.016,0 -1.84573,-0.27093 -2.4892,-0.8128 -0.6096,-0.57573 -0.9144,-1.3208 -0.9144,-2.2352 v -8.2804 h -18.3896 v 8.2804 c 0,0.9144 -0.32173,1.65947 -0.9652,2.2352 -0.6096,0.54187 -1.4224,0.8128 -2.4384,0.8128 z m 21.7932,-16.5608 v -4.4196 c 0,-3.38667 -0.77893,-5.92667 -2.3368,-7.62 -1.524,-1.7272 -3.81,-2.5908 -6.858,-2.5908 -6.12987,0 -9.1948,3.4036 -9.1948,10.2108 v 4.4196 z m 10.50129,16.6116 c -1.04987,0 -1.86267,-0.27093 -2.4384,-0.8128 -0.57573,-0.54187 -0.8636,-1.28693 -0.8636,-2.2352 v -19.812 c 0,-0.98213 0.27093,-1.7272 0.8128,-2.2352 0.57573,-0.54187 1.3716,-0.8128 2.3876,-0.8128 1.016,0 1.81187,0.27093 2.3876,0.8128 0.57573,0.54187 0.8636,1.28693 0.8636,2.2352 v 1.6764 c 1.04987,-1.35467 2.26907,-2.3876 3.6576,-3.0988 1.4224,-0.74507 2.9464,-1.1176 4.572,-1.1176 h 1.1176 c 1.1083,0 1.91347,0.23707 2.4892,0.7112 0.6096,0.44027 0.9144,1.08373 0.9144,1.9304 0,0.84667 -0.3048,1.50707 -0.9144,1.9812 -0.57573,0.44027 -1.40547,0.6604 -2.4892,0.6604 h -1.7272 c -2.26907,0 -4.08093,0.72813 -5.4356,2.1844 -1.3208,1.4224 -1.9812,3.38667 -1.9812,5.8928 v 8.9916 c 0,0.94827 -0.3048,1.69333 -0.9144,2.2352 -0.6096,0.54187 -1.4224,0.8128 -2.4384,0.8128 z m 26.09683,0 c -4.53813,0 -8.06027,-1.13453 -10.5664,-3.4036 -2.50613,-2.30293 -3.7592,-5.50333 -3.7592,-9.6012 0,-4.09787 1.25307,-7.2644 3.7592,-9.4996 2.50613,-2.26907 6.02827,-3.4036 10.5664,-3.4036 2.40453,0 4.572,0.4064 6.5024,1.2192 1.96427,0.77893 3.5052,1.89653 4.6228,3.3528 0.4064,0.508 0.6096,1.04987 0.6096,1.6256 0,0.88053 -0.4572,1.65947 -1.3716,2.3368 -0.4064,0.3048 -0.9144,0.4572 -1.524,0.4572 -0.47413,0 -0.9652,-0.1016 -1.4732,-0.3048 -0.508,-0.23707 -0.94827,-0.5588 -1.3208,-0.9652 -0.67733,-0.77893 -1.55787,-1.3716 -2.6416,-1.778 -1.04987,-0.44027 -2.1844,-0.6604 -3.4036,-0.6604 -2.40453,0 -4.28413,0.67733 -5.6388,2.032 -1.3208,1.3208 -1.9812,3.18347 -1.9812,5.588 0,2.47227 0.6604,4.38573 1.9812,5.7404 1.35467,1.3208 3.23427,1.9812 5.6388,1.9812 2.67547,0 4.96147,-0.89747 6.858,-2.6924 0.94827,-0.94827 1.8796,-1.4224 2.794,-1.4224 0.6096,0 1.1684,0.2032 1.6764,0.6096 0.8128,0.64347 1.2192,1.4224 1.2192,2.3368 0,0.57573 -0.23707,1.13453 -0.7112,1.6764 -1.55787,1.65947 -3.302,2.87867 -5.2324,3.6576 -1.89653,0.74507 -4.09787,1.1176 -6.604,1.1176 z m 21.11842,0 c -2.09973,0 -3.9624,-0.3556 -5.588,-1.0668 -1.59173,-0.7112 -2.8448,-1.69333 -3.7592,-2.9464 -0.88053,-1.28693 -1.3208,-2.76013 -1.3208,-4.4196 0,-2.50613 1.03293,-4.50427 3.0988,-5.9944 2.09973,-1.49013 4.9276,-2.2352 8.4836,-2.2352 3.08187,0 5.55413,0.6604 7.4168,1.9812 v -1.2192 c 0,-1.59173 -0.49107,-2.81093 -1.4732,-3.6576 -0.98213,-0.84667 -2.40453,-1.27 -4.2672,-1.27 -2.77707,0 -5.16467,0.59267 -7.1628,1.778 -0.84667,0.47413 -1.5748,0.7112 -2.1844,0.7112 -0.74507,0 -1.40547,-0.33867 -1.9812,-1.016 -0.37253,-0.47413 -0.5588,-0.9652 -0.5588,-1.4732 0,-0.84667 0.44027,-1.59173 1.3208,-2.2352 1.2192,-0.88053 2.76013,-1.5748 4.6228,-2.0828 1.86267,-0.508 3.82693,-0.762 5.8928,-0.762 3.92853,0 7.0104,0.89747 9.2456,2.6924 2.26907,1.76107 3.4036,4.19947 3.4036,7.3152 v 13.0556 c 0,0.88053 -0.3048,1.5748 -0.9144,2.0828 -0.57573,0.508 -1.3716,0.762 -2.3876,0.762 -1.04987,0 -1.86267,-0.23707 -2.4384,-0.7112 -0.57573,-0.47413 -0.8636,-1.15147 -0.8636,-2.032 v -0.5588 c -1.9304,2.20133 -4.79213,3.302 -8.5852,3.302 z m 1.5748,-4.6736 c 2.1336,0 3.79307,-0.32173 4.9784,-0.9652 1.2192,-0.67733 1.8288,-1.60867 1.8288,-2.794 0,-1.1176 -0.6096,-2.01507 -1.8288,-2.6924 -1.2192,-0.67733 -2.8448,-1.016 -4.8768,-1.016 -1.8288,0 -3.2512,0.32173 -4.2672,0.9652 -0.98213,0.64347 -1.4732,1.55787 -1.4732,2.7432 0,1.18533 0.49107,2.11667 1.4732,2.794 0.98213,0.64347 2.37067,0.9652 4.1656,0.9652 z m 26.60484,4.6736 c -2.6416,0 -4.99533,-0.508 -7.0612,-1.524 -2.032,-1.04987 -3.62373,-2.55693 -4.7752,-4.5212 -1.15147,-1.96427 -1.7272,-4.2672 -1.7272,-6.9088 0,-2.6416 0.54187,-4.9276 1.6256,-6.858 1.1176,-1.96427 2.70933,-3.47133 4.7752,-4.5212 2.06587,-1.04987 4.50427,-1.5748 7.3152,-1.5748 2.06587,0 3.81,0.33867 5.2324,1.016 1.4224,0.64347 2.76013,1.6256 4.0132,2.9464 l 0.0508,-14.0716 c 0,-0.94827 0.3048,-1.69333 0.9144,-2.2352 0.6096,-0.54187 1.4224,-0.8128 2.4384,-0.8128 1.016,0 1.81187,0.27093 2.3876,0.8128 0.6096,0.508 0.9144,1.25307 0.9144,2.2352 v 32.9692 c 0,0.98213 -0.3048,1.74413 -0.9144,2.286 -0.57573,0.508 -1.3716,0.762 -2.3876,0.762 -1.016,0 -1.8288,-0.27093 -2.4384,-0.8128 -0.6096,-0.54187 -0.9144,-1.28693 -0.9144,-2.2352 v -1.016 c -2.26907,2.70933 -5.41867,4.064 -9.4488,4.064 z m 1.016,-5.2832 c 2.6416,0 4.70747,-0.67733 6.1976,-2.032 1.49013,-1.35467 2.2352,-3.23427 2.2352,-5.6388 0,-2.40453 -0.74507,-4.28413 -2.2352,-5.6388 -1.49013,-1.35467 -3.556,-2.032 -6.1976,-2.032 -2.47227,0 -4.40267,0.67733 -5.7912,2.032 -1.38853,1.35467 -2.0828,3.23427 -2.0828,5.6388 0,2.40453 0.69427,4.28413 2.0828,5.6388 1.38853,1.35467 3.31893,2.032 5.7912,2.032 z m 19.44203,-21.12857 c -1.25307,0 -2.26907,-0.33867 -3.048,-1.016 -0.74507,-0.7112 -1.1176,-1.6256 -1.1176,-2.7432 0,-1.1176 0.37253,-2.01507 1.1176,-2.6924 0.77893,-0.7112 1.79493,-1.0668 3.048,-1.0668 1.25307,0 2.26907,0.3556 3.048,1.0668 0.8128,0.67733 1.2192,1.5748 1.2192,2.6924 0,1.1176 -0.4064,2.032 -1.2192,2.7432 -0.77893,0.67733 -1.79493,1.016 -3.048,1.016 z m 0.0508,26.41177 c -1.04987,0 -1.86267,-0.27093 -2.4384,-0.8128 -0.57573,-0.54187 -0.8636,-1.28693 -0.8636,-2.2352 v -19.812 c 0,-0.94827 0.28787,-1.69333 0.8636,-2.2352 0.57573,-0.54187 1.38853,-0.8128 2.4384,-0.8128 1.016,0 1.8288,0.27093 2.4384,0.8128 0.6096,0.54187 0.9144,1.28693 0.9144,2.2352 v 19.812 c 0,0.94827 -0.3048,1.69333 -0.9144,2.2352 -0.6096,0.54187 -1.4224,0.8128 -2.4384,0.8128 z m 14.56529,0 c -2.09973,0 -3.9624,-0.3556 -5.588,-1.0668 -1.59173,-0.7112 -2.8448,-1.69333 -3.7592,-2.9464 -0.88053,-1.28693 -1.3208,-2.76013 -1.3208,-4.4196 0,-2.50613 1.03293,-4.50427 3.0988,-5.9944 2.09973,-1.49013 4.9276,-2.2352 8.4836,-2.2352 3.08187,0 5.55413,0.6604 7.4168,1.9812 v -1.2192 c 0,-1.59173 -0.49107,-2.81093 -1.4732,-3.6576 -0.98213,-0.84667 -2.40453,-1.27 -4.2672,-1.27 -2.77707,0 -5.16467,0.59267 -7.1628,1.778 -0.84667,0.47413 -1.5748,0.7112 -2.1844,0.7112 -0.74507,0 -1.40547,-0.33867 -1.9812,-1.016 -0.37253,-0.47413 -0.5588,-0.9652 -0.5588,-1.4732 0,-0.84667 0.44027,-1.59173 1.3208,-2.2352 1.2192,-0.88053 2.76013,-1.5748 4.6228,-2.0828 1.86267,-0.508 3.82693,-0.762 5.8928,-0.762 3.92853,0 7.0104,0.89747 9.2456,2.6924 2.26907,1.76107 3.4036,4.19947 3.4036,7.3152 v 13.0556 c 0,0.88053 -0.3048,1.5748 -0.9144,2.0828 -0.57573,0.508 -1.15812,-0.77183 -2.17412,-0.77183 -1.04987,0 -1.13054,0.25485 -1.70627,-0.21928 -0.57574,-0.47414 -0.12302,-0.91271 -0.94686,-1.22354 l -0.86235,-0.32535 c -1.9304,2.20133 -4.79213,3.302 -8.5852,3.302 z m 1.5748,-4.6736 c 2.1336,0 3.79307,-0.32173 4.9784,-0.9652 1.2192,-0.67733 1.8288,-1.60867 1.8288,-2.794 0,-1.1176 -0.6096,-2.01507 -1.8288,-2.6924 -1.2192,-0.67733 -2.8448,-1.016 -4.8768,-1.016 -1.8288,0 -3.2512,0.32173 -4.2672,0.9652 -0.98213,0.64347 -1.4732,1.55787 -1.4732,2.7432 0,1.18533 0.49107,2.11667 1.4732,2.794 0.98213,0.64347 2.37067,0.9652 4.1656,0.9652 z" id="text2-5" style="font-weight:bold;font-size:50.8px;font-family:Kodchasan;-inkscape-font-specification:'Kodchasan Bold';letter-spacing:-4.48469px;fill:#fb9e27;fill-opacity:1;stroke:#fb9e27;stroke-width:0.08;stroke-opacity:1" aria-label="Arcadia" />
        <path style="fill:#fb9e27;fill-opacity:1;stroke:#fb9e27;stroke-width:0.0799999;stroke-dasharray:none;stroke-opacity:1" d="m -596.90916,339.80156 c 0.0345,0.1985 2.8983,3.55913 7.16173,-2.91485 3.5754,-5.42922 1.5101,0.72662 1.5101,0.72662 -4.13702,8.54739 -15.71071,8.14103 -15.33369,1.98984 z" id="path2" />
        <path style="fill:#c97524;fill-opacity:1;stroke:#c97524;stroke-width:0.0799999;stroke-dasharray:none;stroke-opacity:1" d="m -588.96191,336.79847 c 0,0 -1.55205,-2.69791 0.12978,-4.20484 1.6818,-1.50696 3.32957,-2.29127 3.32957,-2.29127 0,0 1.94077,2.62819 0.94639,4.16558 -0.99441,1.53736 -4.40574,2.33053 -4.40574,2.33053 z" id="path3" />
      </g>
    </svg>
  </a>
  <nav>
    <ul class="dashboard-panel__list">
      <?php
      // VETERINARY
      if (Security::isVeterinary()) { ?>
        <li class="dashboard-panel__list__item">
          <a id="menu-report" href="/dashboard/rapport-animaux">
            <img height="32px" src="/assets/images/icons/note.svg" alt="" srcset="">
            <span>rapport des animaux</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/dashboard/alimentation-animaux">
            <img height="32px" src="/assets/images/icons/food-drumstick.svg" alt="" srcset="">
            <span>alimentation des animaux</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/dashboard/commentaire-habitats">
            <img height="32px" src="/assets/images/icons/chat.svg" alt="" srcset="">
            <span>état des habitats</span></a>
        </li>
      <?php }
      // EMPLOYEE
      if (Security::isEmployee()) { ?>
        <li class="dashboard-panel__list__item">
          <a href="/dashboard/alimentation-animaux">
            <img height="32px" src="/assets/images/icons/food-drumstick.svg" alt="" srcset="">
            <span>alimentation des animaux</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/dashboard/avis" id="menu-advice">
            <img height="32px" src="/assets/images/icons/star.svg" alt="" srcset="">
            <span>avis</span></a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/dashboard/services">
            <img height="32px" src="/assets/images/icons/account-wrench.svg" alt="wrench icon" srcset="">
            <span>services</span> </a>
        </li>
      <?php }
      // ADMIN
      if (Security::isAdmin()) { ?>
        <li class="dashboard-panel__list__item">
          <a href="/dashboard" id="menu-dashboard">
            <img height="32px" src="/assets/images/icons/viewDashboard.svg" alt="dashboard icon" srcset="">
            <span>dashboard</span></a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/dashboard/utilisateurs">
            <img height="32px" src="/assets/images/icons/account-group.svg" alt="user icon" srcset="">
            <span>utilisateurs</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/dashboard/habitats">
            <img height="32px" src="/assets/images/icons/home.svg" alt="house icon" srcset="">
            <span>habitats</span>
          </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/dashboard/animaux">
            <img height="32px" src="/assets/images/icons/paw.svg" alt="paw icon" srcset="">
            <span>animaux</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/dashboard/horaires">
            <img height="32px" src="/assets/images/icons/clock-outline.svg" alt="clock icon" srcset="">
            <span>horaires</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/dashboard/commentaire-habitats">
            <img height="32px" src="/assets/images/icons/chat.svg" alt="" srcset="">
            <span>état des habitats</span></a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/dashboard/rapport-animaux">
            <img height="32px" src="/assets/images/icons/note.svg" alt="" srcset="">
            <span>rapport des animaux</span> </a>
        </li>
        <li class="dashboard-panel__list__item">
          <a href="/dashboard/services">
            <img height="32px" src="/assets/images/icons/account-wrench.svg" alt="wrench icon" srcset="">
            <span>services</span> </a>
        </li>
      <?php } ?>
    </ul>
  </nav>

</aside>