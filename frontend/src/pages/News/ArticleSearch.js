import { useContext, useState, useEffect } from 'react';
import { TextField, Button, Box, MenuItem, Typography, CircularProgress, Pagination, Card, CardContent } from '@mui/material';
import api from '../../services/api';
import { HttpStatusCode } from 'axios';
import { AuthContext } from '../../context/AuthContext';
import { v4 as uuidv4 } from 'uuid';
import { Link } from 'react-router-dom';

const ArticleSearch = () => {
  const { logout }= useContext(AuthContext);
  const [keyword, setKeyword] = useState('');
  const [category, setCategory] = useState('');
  const [source, setSource] = useState('');
  const [date, setDate] = useState('');
  const [articles, setArticles] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [sources, setSources] = useState([]);
  const [categories, setCategories] = useState([]);

  useEffect(() => {
    const fetchOptions = async () => {
      try {
        setLoading(true);
        setError('');
        const response = await api.get('/preference/options');

        setSources(response.data.sources);
        setCategories(response.data.categories);
      } catch (error) {
        if (error.status === HttpStatusCode.Unauthorized) {
          logout();
        } else {
          setError(`An error occurred: ${error.code}`);
        }
      }finally {
        setLoading(false);
      }
    };

    fetchOptions();
  }, [logout]);

  const fetchData = async (page) => {
    try {
      setLoading(true);
      setError('');

      const response = await api.get('/articles/search', { 
        params: { keyword, category, source, date, page } 
      });

      setArticles(response.data.articles.data);
      setCurrentPage(response.data.articles.current_page);
      setTotalPages(response.data.articles.last_page);
    } catch (error) {
      if (error.status === HttpStatusCode.Unauthorized) {
        logout();
      } else {
        setError(`An error occurred: ${error.code}`);
      }
    } finally {
      setLoading(false);
    }
  };

  const handlePageChange = async (event, value) => {
    await fetchData(value);
  };

  return (
    <Box>
      {loading && <CircularProgress />}
      {error && <Typography>{error}</Typography>}
      
      <TextField 
        label="Search Articles" 
        value={keyword} 
        onChange={(e) => setKeyword(e.target.value)} 
        fullWidth 
        margin="normal"
      />

      <TextField
        select
        label="Select Category"
        value={category}
        onChange={(e) => setCategory(e.target.value)}
        fullWidth
        variant="outlined"
        margin="normal"
      >
        {categories.map((category) => (
          <MenuItem key={uuidv4()} value={category}>
            {category}
          </MenuItem>
        ))}
      </TextField>   

    <TextField
        select
        label="Select Source"
        value={source}
        onChange={(e) => setSource(e.target.value)}
        fullWidth
        variant="outlined"
        margin="normal"
      >
        {sources.map((source) => (
          <MenuItem key={uuidv4()} value={source}>
            {source}
          </MenuItem>
        ))}
      </TextField>    

      <TextField
        label="Date"
        type="date"
        value={date}
        onChange={(e) => setDate(e.target.value)}
        InputLabelProps={{
          shrink: true,
        }}
        fullWidth
        margin="normal"
      />

      <Button onClick={async() => await fetchData(1)} disabled={loading} variant="contained" color="primary" fullWidth>
        Search
      </Button>

      <Box marginTop={2}>
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
              onChange={async(e, v) => await handlePageChange(e, v)}
              color="primary"
              sx={{ mt: 2 }}
            />
          </Box>
      ) : (
        !loading && <Typography>No articles found.</Typography>
      )}
      </Box>
      
    </Box>
  );
};

export default ArticleSearch;
