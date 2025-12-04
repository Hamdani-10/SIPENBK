/**
 * Copyright (c) 2025 Hamdani Kevin
 * This project is part of the SIPENBK Counseling Scheduling System.
 * All rights reserved.
 */
// Teks berjalan halaman admin
document.addEventListener("DOMContentLoaded", function () {
    const name = window.userName ?? "Admin";
    const greetingElement = document.getElementById("greeting");

    const hours = new Date().getHours();
    let waktu;

    if (hours < 12) {
        waktu = "Selamat Pagi";
    } else if (hours < 15) {
        waktu = "Selamat Siang";
    } else if (hours < 18) {
        waktu = "Selamat Sore";
    } else {
        waktu = "Selamat Malam";
    }

    // Daftar pesan
    const messages = [
        `${waktu}, ${name} 👋`,
        `Selamat datang di SIPENBK, ${name} 🎓`,
        `Semoga harimu menyenangkan dan produktif, ${name} 🌟`
    ];

    let index = 0;
    let charIndex = 0;
    let typingSpeed = 50;
    let pauseBetween = 2000;

    function typeMessage() {
        if (!greetingElement) return;

        // Ambil pesan sekarang
        const currentMessage = messages[index];

        // Ketik huruf per huruf
        if (charIndex < currentMessage.length) {
            greetingElement.innerHTML = currentMessage.substring(0, charIndex + 1);
            charIndex++;
            setTimeout(typeMessage, typingSpeed);
        } else {
            // Setelah selesai satu pesan, tunggu, lalu lanjut ke pesan berikutnya
            setTimeout(() => {
                charIndex = 0;
                index = (index + 1) % messages.length;
                greetingElement.innerHTML = ""; // Reset sebelum ketik berikutnya
                typeMessage();
            }, pauseBetween);
        }
    }

    typeMessage();
});

// teks berjalan halaman guru
document.addEventListener("DOMContentLoaded", function () {
    const name = window.userName ?? "namaGuru";
    const textElement = document.getElementById("text-running");

    const hours = new Date().getHours();
    let waktu;

    if (hours < 12) {
        waktu = "Selamat Pagi";
    } else if (hours < 15) {
        waktu = "Selamat Siang";
    } else if (hours < 18) {
        waktu = "Selamat Sore";
    } else {
        waktu = "Selamat Malam";
    }

    // Daftar pesan
    const messages = [
        `${waktu}, ${name} 👋`,
        `Selamat datang di SIPENBK, ${name} 🎓`,
        `Semoga harimu menyenangkan dan produktif, ${name} 🌟`
    ];

    let index = 0;
    let charIndex = 0;
    let typingSpeed = 50;
    let pauseBetween = 2000;

    function typeMessage() {
        if (!textElement) return;

        // Ambil pesan sekarang
        const currentMessage = messages[index];

        // Ketik huruf per huruf
        if (charIndex < currentMessage.length) {
            textElement.innerHTML = currentMessage.substring(0, charIndex + 1);
            charIndex++;
            setTimeout(typeMessage, typingSpeed);
        } else {
            // Setelah selesai satu pesan, tunggu, lalu lanjut ke pesan berikutnya
            setTimeout(() => {
                charIndex = 0;
                index = (index + 1) % messages.length;
                textElement.innerHTML = ""; // Reset sebelum ketik berikutnya
                typeMessage();
            }, pauseBetween);
        }
    }

    typeMessage();
});

// teks berjalan halaman siswa
document.addEventListener("DOMContentLoaded", function () {
    const name = window.userName ?? "namaSiswa";
    const textElement = document.getElementById("text-jalan");

    const hours = new Date().getHours();
    let waktu;

    if (hours < 12) {
        waktu = "Selamat Pagi 🌅";
    } else if (hours < 15) {
        waktu = "Selamat Siang ☀️";
    } else if (hours < 18) {
        waktu = "Selamat Sore 🌇";
    } else {
        waktu = "Selamat Malam 🌙";
    }

    const messages = [
        `${waktu}, ${name} 👋`,
        `Selamat datang di SIPENBK, ${name} 📚`,
        `Jangan lupa jadwalkan sesi BK-mu ya, ${name} 👍`,
        `Semoga harimu penuh semangat dan makna, ${name} ✨`
    ];

    let index = 0;
    let charIndex = 0;
    let typingSpeed = 50;
    let pauseBetween = 2000;

    function typeMessage() {
        if (!textElement) return;

        const currentMessage = messages[index];

        if (charIndex < currentMessage.length) {
            textElement.innerHTML = currentMessage.substring(0, charIndex + 1);
            charIndex++;
            setTimeout(typeMessage, typingSpeed);
        } else {
            setTimeout(() => {
                charIndex = 0;
                index = (index + 1) % messages.length;
                textElement.innerHTML = "";
                typeMessage();
            }, pauseBetween);
        }
    }

    typeMessage();
});
