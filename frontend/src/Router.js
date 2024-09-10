import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import Register from './pages/Auth/Register';
import Login from './pages/Auth/Login';
import NewsFeed from './pages/News/NewsFeed';
import ArticleSearch from './pages/News/ArticleSearch';
import Preferences from './pages/News/Prerefences';

const AppRouter = () => {
  return (
    <Router>
      <Routes>
        <Route path="/register" element={<Register />} />
        <Route path="/login" element={<Login />} />
        <Route path="/feed" element={<NewsFeed />} />
        <Route path="/search" element={<ArticleSearch />} />
        <Route path="/preferences" element={<Preferences />} />
      </Routes>
    </Router>
  );
};

export default AppRouter;
