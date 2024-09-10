import { useCallback, useEffect, useState } from 'react';
import { TextField, Button, Box, MenuItem, Typography, CircularProgress, Pagination } from '@mui/material';
import api from '../../services/api';
import { HttpStatusCode } from 'axios';
import { useNavigate } from 'react-router-dom';

const ArticleSearch = () => {
  const [keyword, setKeyword] = useState('');
  const [category, setCategory] = useState('');
  const [source, setSource] = useState('');
  const [date, setDate] = useState('');
  const [articles, setArticles] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const navigate = useNavigate();


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
        navigate("/login");
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

  const handleSearch = async () => {
    await fetchData(1)
  }

  return (
    <Box>
      {loading && <CircularProgress />}
      {error && <Typography>{error}</Typography>}
      {/* Keyword Search */}
      <TextField 
        label="Search Articles" 
        value={keyword} 
        onChange={(e) => setKeyword(e.target.value)} 
        fullWidth 
        margin="normal"
      />

      {/* Category Filter */}
      <TextField
        select
        label="Category"
        value={category}
        onChange={(e) => setCategory(e.target.value)}
        fullWidth
        margin="normal"
      >
        <MenuItem value="technology">Technology</MenuItem>
        <MenuItem value="business">Business</MenuItem>
        <MenuItem value="health">Health</MenuItem>
        
      </TextField>

      <TextField
        label="Source"
        value={source}
        onChange={(e) => setSource(e.target.value)}
        fullWidth
        margin="normal"
      />

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

      <Button onClick={async() => await fetchData(1)} variant="contained" color="primary" fullWidth>
        Search
      </Button>

      <Box marginTop={2}>
      {articles.length ? (
          <>
            {articles.map(article => (
              <div key={article.id}>
                <h3>{article.title}</h3>
                <p>{article.content}</p>
              </div>
            ))}

            <Pagination
              count={totalPages}
              page={currentPage} // Ensure page is always controlled
              onChange={async(e, v) => await handlePageChange(e, v)}
              color="primary"
              sx={{ mt: 2 }}
            />
          </>
        ) : (
          <p>No articles found</p>
        )}
      </Box>
      
    </Box>
  );
};

export default ArticleSearch;
