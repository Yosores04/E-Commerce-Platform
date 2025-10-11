# IndoMarket Frontend

Modern Vue.js 3 frontend for the IndoMarket multi-vendor e-commerce platform.

## Tech Stack

- **Vue.js 3.5.13** - Progressive JavaScript framework with Composition API
- **Vite 7.1.9** - Fast build tool and development server
- **Vue Router 4.5.1** - Official router for Vue.js
- **Pinia 2.3.1** - State management (Vuex successor)
- **Axios 1.7.9** - Promise-based HTTP client
- **TailwindCSS 3.4.17** - Utility-first CSS framework

## Features Implemented

✅ **Authentication System** - Registration, login, logout, protected routes, role-based access  
✅ **Product Browsing** - Home page, product listing, filters, search, pagination  
✅ **Shopping Cart** - Add/remove items, update quantities, real-time totals  
✅ **Responsive Layout** - Header, navigation, footer, mobile-friendly  
✅ **API Integration** - Axios interceptors, service layer, error handling  
✅ **State Management** - Pinia stores for auth and cart

## Development

```bash
# Install dependencies
npm install

# Start dev server (http://localhost:5173)
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview
```

## Project Structure

```
src/
├── components/     # Reusable components (Header, Footer, ProductCard)
├── layouts/        # Page layouts (DefaultLayout)
├── views/          # Page components (Home, Products, Cart, etc.)
├── router/         # Vue Router configuration
├── services/       # API service layer (auth, products, cart, orders)
├── stores/         # Pinia stores (auth, cart)
├── App.vue         # Root component
├── main.js         # Entry point
└── style.css       # Global styles with Tailwind
```

## API Configuration

Backend API: `http://127.0.0.1:8000/api`

Update in `src/services/api.js` if needed.

## Routes

| Path            | Component         | Access        |
| --------------- | ----------------- | ------------- |
| `/`             | HomePage          | Public        |
| `/products`     | ProductsPage      | Public        |
| `/products/:id` | ProductDetailPage | Public        |
| `/cart`         | CartPage          | Public        |
| `/checkout`     | CheckoutPage      | Auth Required |
| `/orders`       | OrdersPage        | Auth Required |
| `/login`        | LoginPage         | Guest Only    |
| `/register`     | RegisterPage      | Guest Only    |
| `/vendor/*`     | Vendor Pages      | Vendor Role   |
| `/admin/*`      | Admin Pages       | Admin Role    |

## Next Steps

- Complete checkout flow
- Build order management pages
- Develop vendor dashboard
- Create admin panel
- Integrate IndoMarket theme

## Known Issues

- Node.js 20.17.0 shows version warning (requires 20.19+) but works
- Some placeholder pages need full implementation

For detailed documentation, see the full README in the project root.
