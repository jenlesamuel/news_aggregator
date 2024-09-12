import { useCallback, useContext, useEffect, useState } from 'react';
import { Box, CircularProgress, Typography, Card, CardContent, Pagination, Button } from '@mui/material';
import { Link, useNavigate } from 'react-router-dom';
import api from '../../services/api';
import { HttpStatusCode } from 'axios';
import { AuthContext } from '../../context/AuthContext';

const NewsFeed = () => {
  const { logout } = useContext(AuthContext);
  const [articles, setArticles] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const navigate = useNavigate();

  const fetchArticles = useCallback(async (page = 1) => {
    try {
      setLoading(true);
      setError('');

      const response = await api.get(`/newsfeed`, {
        params: { page }
      });

      setArticles(response.data.articles.data);
      setCurrentPage(response.data.articles.current_page);
      setTotalPages(response.data.articles.last_page);
    } catch (err) {
      if (err.status === HttpStatusCode.Unauthorized) {
        logout();
      } else {
        setError('Failed to load articles.');
      }
    } finally {
      setLoading(false);
    }
  }, [logout]);

  useEffect(() => {
    fetchArticles(currentPage);
  }, [currentPage, fetchArticles]);

  const handlePageChange = (event, value) => {
    setCurrentPage(value);
    fetchArticles(value);
  };

  return (
    <Box>
      <Typography variant="h4" gutterBottom>
        Your Articles
      </Typography>

      {loading && <CircularProgress />}
      {error && <Typography color="error">{error}</Typography>}

      {articles.length > 0 ? (
        <Box>
          {articles.map((article) => (
            <Card key={article.id} variant="outlined" sx={{ mb: 2 }}>
              <CardContent>
                <Typography variant="h5">
                  <Link 
                      to={article.web_url}
                      style={{ textDecoration: 'none' }}
                      target="_blank"                   
                      rel="noopener noreferrer">
                        {article.title}
                    </Link>
                  </Typography>
                <Typography variant="body2" color="textSecondary" gutterBottom>
                  {article.author} | {article.category} | {article.source}
                </Typography>
                <Typography variant="body1">{article.content}</Typography>
              </CardContent>
            </Card>
          ))}

          <Pagination
            count={totalPages}
            page={currentPage}
            onChange={handlePageChange}
            color="primary"
            sx={{ mt: 2 }}
          />
        </Box>
      ) : (
        !loading && <Typography>No articles found based on your preferences.</Typography>
      )}

      <Button variant="contained" onClick={() => navigate('/settings')} sx={{ mt: 3 }}>
        Update Preferences
      </Button>
    </Box>
  );
};

export default NewsFeed;
