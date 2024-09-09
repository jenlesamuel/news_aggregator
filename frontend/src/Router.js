import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import Register from './pages/Auth/Register';
import Login from './pages/Auth/Login';
import NewsFeed from './pages/News/NewsFeed';

const AppRouter = () => {
  return (
    <Router>
      <Routes>
        <Route path="/register" element={<Register />} />
        <Route path="/login" element={<Login />} />
        <Route path="/feed" element={<NewsFeed />} />
      </Routes>
    </Router>
  );
};

export default AppRouter;
