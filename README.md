# DayTrace - Daily Routine, Sleep & Micro-Journaling Platform

A centralized, web-based personal utility platform designed to simplify personal routine management, wellness tracking, and daily reflection without app fatigue or feature bloat.

## 📌 Project Overview

Maintaining a balanced daily routine across work, study, physical recovery, and mental well-being is increasingly difficult. Popular productivity tools are often fragmented, complex, and filled with intrusive notifications or steep learning curves.

**DayTrace** solves this problem by consolidating habit tracking, sleep and energy logging, and qualitative micro-journaling into a single, intuitive, and responsive single-page web dashboard. Users can complete their entire end-of-day reflection in under two minutes while keeping total ownership of their personal history.

---

## ✨ Key Features

- **⚡ Habit Tracking Module**: Easily check off core daily habits (e.g., exercise, reading, hydration) and track completion status.
- **🌙 Sleep & Recovery Logger**: Capture total sleep duration along with a subjective 1-to-5 energy rating to monitor physical recovery and sleep hygiene over time.
- **🖋️ Micro-Journaling**: Author structured 3-line daily reflections capturing daily highlights, growth areas, and gratitude statements.
- **📊 Historical Log & Full CRUD Support**: Review chronological history in interactive tables, with capabilities to Create, Read, Update, and Delete past logs.
- **🔐 Secure Authentication**: Multi-user support featuring server-side session management and password encryption to isolate user data domains cleanly.
- **⚙️ Admin Portal**: System administration interface for user account management, role allocation, and tracking platform metrics.

---

## 🛠️ Tech Stack & Architecture

### Front-End Presentation Layer
- **HTML5 & CSS3**: Semantic page structures and responsive layouts utilizing modern CSS Flexbox/Grid.
- **JavaScript (Vanilla / ES6)**: Dynamic DOM manipulation, modal interactions, tab views, and client-side form validation.

### Back-End & Database Execution Layer
- **PHP (v8.x)**: Server-side business logic, session validation, route protection, and database interaction using PDO/MySQLi.
- **MySQL Database**: Normalized relational database (up to 3NF) utilizing Foreign Keys and cascade rules for transactional integrity.
- **Apache Web Server**: Hosted on local development environments (XAMPP / WAMP / Docker containers).

---

## 🗄️ Database Architecture (ERD Summary)

The system is organized into modular microservice schemas normalized to **3rd Normal Form (3NF)**:

1. **User Authentication Module**: `USERS` (Core credentials and authentication) and `USER_PROFILES` (User preferences).
2. **Habit Formation Module**: `HABITS` (Global habit definitions per user) and `HABIT_LOGS` (Daily completion execution checkmarks).
3. **Sleep & Recovery Module**: `SLEEP_GOALS` (Baseline target sleep hours) and `SLEEP_LOGS` (Daily hours slept and 1–5 energy ratings).
4. **Micro-Journaling Module**: `JOURNAL_ENTRIES` (3-line structured reflections) and `JOURNAL_TAGS` (Categorization tags).

---
