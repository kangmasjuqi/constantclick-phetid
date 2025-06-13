# PHET-ID: Personalized Health Trends & Early Insight Dashboard

## 🩺 Overview

PHET-ID is a comprehensive web application designed to simplify blood test tracking and empower users to take proactive control of their health. By transforming confusing lab reports into clear, actionable insights through intuitive visualizations and trend analysis, PHET-ID makes health monitoring accessible and affordable for everyone.

### ✨ Core Mission
- **Simplify** blood test data interpretation
- **Empower** proactive health management
- **Encourage** regular health monitoring (2-3 times yearly)
- **Provide** early health insights through trend analysis

## 🚀 Key Features

### 📊 Health Dashboard
- **Interactive Charts**: Visualize blood marker trends over time with dynamic line and bar charts
- **Traffic Light System**: Instantly identify optimal, caution, and critical marker levels
- **Trend Analysis**: AI-powered insights showing whether markers are improving, stable, or declining
- **Multi-timeframe Views**: Analyze data across different periods (3 months, 6 months, 1 year, all time)

### 📝 Smart Data Entry
- **Intuitive Forms**: Easy-to-use interface for manual lab result input
- **Test Panel Support**: Predefined panels (Comprehensive Metabolic, Lipid Panel, etc.)
- **Flexible Marker Input**: Support for 50+ common blood markers
- **Historical Data Management**: Edit, update, or delete previous entries

### 🔔 Health Reminders
- **Testing Schedules**: Automated reminders for your next blood test
- **Email Notifications**: Stay on track with your health monitoring goals
- **Customizable Intervals**: Set reminders based on your testing frequency

### 📚 Educational Resources
- **Marker Explanations**: Clear descriptions of what each blood marker means
- **Healthy Ranges**: Reference ranges for optimal health
- **General Health Tips**: Non-medical guidance based on trends (with appropriate disclaimers)

## 🛠 Tech Stack

### Backend
- **Framework**: Laravel 10 (PHP 8.1+)
- **Database**: MySQL 5.0
- **Authentication**: Laravel Sanctum for API token management
- **API**: RESTful API architecture
- **Testing**: PHPUnit with comprehensive test coverage
- **Code Quality**: PSR-12 standards, PHPStan static analysis

### Frontend
- **Framework**: Vue.js 3 with Composition API
- **Build Tool**: Vite for fast development and optimized builds
- **Styling**: Tailwind CSS for responsive, modern UI
- **Charts**: Chart.js for interactive data visualizations
- **State Management**: Pinia for centralized state management
- **HTTP Client**: Axios for API communication

### Infrastructure
- **Containerization**: Docker for consistent development environment
- **Process Management**: Laravel Horizon for queue management
- **Caching**: Redis for session and application caching
- **Storage**: Local filesystem with S3-compatible cloud storage support

## 🏗 Architecture

### Database Schema
```
users
├── test_entries
│   ├── test_panels
│   └── marker_values
│       └── markers
└── notifications
```

### API Endpoints [TODO]

### Security Features
- JWT token-based authentication
- Input validation and sanitization
- HTTPS enforcement
- Rate limiting
- CORS protection
- Data encryption at rest

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0
- Redis (optional, for caching)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/phet-id.git
   cd phet-id
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   ```bash
   # Update .env with your database credentials
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=phet_id
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Build frontend assets**
   ```bash
   npm run build
   ```

8. **Start development servers**
   ```bash
   # Terminal 1 - Laravel backend
   php artisan serve
   
   # Terminal 2 - Frontend development
   npm run dev
   ```

Visit `http://localhost:8000` to access the application.

## 🧪 Testing

### Backend Tests
```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test suite
php artisan test --testsuite=Feature
```

### Frontend Tests
```bash
# Run Vue component tests
npm run test

# Run with watch mode
npm run test:watch
```

### Code Quality
```bash
# PHP static analysis
./vendor/bin/phpstan analyse

# JavaScript linting
npm run lint

# Fix code style
composer run cs-fix
npm run lint:fix
```

## 📊 Sample Data

The application includes sample blood test data for demonstration:

- **Demo User**: demo@phet-id.com / password123
- **Sample Tests**: 12 months of comprehensive metabolic panel data
- **Trend Examples**: Shows improving cholesterol, stable glucose, concerning vitamin D levels

## 🔒 Privacy & Security

- **Data Encryption**: All sensitive data encrypted at rest
- **No Medical Advice**: Application provides trends, not medical recommendations
- **HIPAA Considerations**: Built with healthcare data privacy in mind
- **User Control**: Users can export or delete their data at any time

## 📱 Responsive Design

PHET-ID is fully responsive and optimized for:
- 📱 **Mobile devices** (iOS/Android)
- 💻 **Desktop browsers** (Chrome, Firefox, Safari, Edge)
- 📋 **Tablet interfaces** (iPad, Android tablets)

## 🔄 API Documentation [TODO] 

## 🚀 Deployment

### Production Deployment
```bash
# Optimize for production
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Docker Deployment
```bash
# Build and run with Docker
docker-compose up -d
```

### Environment Variables
```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
DB_CONNECTION=mysql
MAIL_MAILER=smtp
REDIS_HOST=redis
```

## 🛣 Roadmap

### Phase 1 ✅ (Current)
- [x] Basic test entry and visualization
- [x] User authentication and profiles
- [x] Trend analysis and insights
- [x] Email reminders

### Phase 2 🔄 (In Progress)
- [ ] OCR integration for lab report scanning
- [ ] Advanced AI insights with ML predictions
- [ ] Mobile app (React Native)
- [ ] Integration with popular fitness trackers

### Phase 3 📋 (Planned)
- [ ] Healthcare provider dashboard
- [ ] Lab integration APIs
- [ ] Advanced reporting and export features
- [ ] Multi-language support

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

### Development Workflow
1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Code Standards
- Follow PSR-12 for PHP code
- Use ESLint/Prettier for JavaScript
- Write comprehensive tests
- Update documentation

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Team

- **Lead Developer**: [Your Name](https://github.com/yourusername)
- **UI/UX Design**: Figma-based responsive design
- **Architecture**: Laravel + Vue.js full-stack implementation

## 📞 Support

- **Documentation**: [Wiki](https://github.com/yourusername/phet-id/wiki)
- **Issues**: [GitHub Issues](https://github.com/yourusername/phet-id/issues)
- **Email**: support@phet-id.com

## 🙏 Acknowledgments

- Laravel community for excellent documentation
- Vue.js team for the robust frontend framework
- Chart.js for beautiful data visualizations
- Tailwind CSS for utility-first styling

---

**⚠️ Disclaimer**: PHET-ID is designed for personal health tracking and trend analysis. It does not provide medical advice, diagnosis, or treatment recommendations. Always consult with healthcare professionals for medical concerns.

**🔐 Privacy**: Your health data is private and secure. We never share personal information with third parties.