import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import Register from './pages/Auth/Register';
import Login from './pages/Auth/Login';
import NewsFeed from './pages/News/NewsFeed';
import ArticleSearch from './pages/News/ArticleSearch';
import Settings from './pages/News/Settings';
import ProtectedRoute from './components/ProtectedRoute';
import PublicRoute from './components/PublicRoute';
import Layout from './components/Layout';

const AppRouter = () => {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Layout />}>
          <Route index element={<ProtectedRoute><NewsFeed /></ProtectedRoute>} />

          <Route path="register" element={<PublicRoute><Register /></PublicRoute>} />
          <Route path="login" element={<PublicRoute><Login /></PublicRoute>} />

          <Route path="search" element={<ProtectedRoute><ArticleSearch /></ProtectedRoute>} />
          <Route path="settings" element={<ProtectedRoute><Settings /></ProtectedRoute>} />
        </Route>
      </Routes>
    </Router>
  );
;}

export default AppRouter;