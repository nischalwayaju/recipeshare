document.addEventListener('DOMContentLoaded', () => {

    const header = document.querySelector('header');


    // Fade-in animation for elements
    const fadeElements = document.querySelectorAll('.fade-in');

    const fadeInObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                fadeInObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });

    fadeElements.forEach(element => {
        fadeInObserver.observe(element);
    });

    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Parallax effect for hero section
    window.addEventListener('scroll', () => {
        const heroContent = document.querySelector('.hero-content');
        const scrollPosition = window.pageYOffset;
        heroContent.style.transform = `translateY(${scrollPosition * 0.4}px)`;

        // Change header background on scroll
        if (scrollPosition > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // Animate team member info on hover
    const teamMembers = document.querySelectorAll('.member');
    teamMembers.forEach(member => {
        member.addEventListener('mouseenter', () => {
            const info = member.querySelector('.member-info');
            info.style.transform = 'translateY(0)';
        });
        member.addEventListener('mouseleave', () => {
            const info = member.querySelector('.member-info');
            info.style.transform = 'translateY(100%)';
        });
    });

    // Animate icons in info boxes
    const infoBoxes = document.querySelectorAll('.info-box');
    infoBoxes.forEach(box => {
        box.addEventListener('mouseenter', () => {
            const icon = box.querySelector('.icon-feature');
            icon.style.animation = 'none';
            setTimeout(() => {
                icon.style.animation = 'float 3s ease-in-out infinite';
            }, 10);
        });
    });
});