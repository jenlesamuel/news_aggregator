import React, { useEffect, useState } from 'react';
import { Box, Button, TextField, MenuItem, Typography, CircularProgress } from '@mui/material';
import { HttpStatusCode } from 'axios';
import api from '../../services/api';
import { useNavigate } from 'react-router-dom';
import { v4 as uuidv4 } from 'uuid';

const Preferences = () => {
  const [sources, setSources] = useState([]);
  const [categories, setCategories] = useState([]);
  const [authors, setAuthors] = useState([]);
  const [selectedSources, setSelectedSources] = useState([]);
  const [selectedCategories, setSelectedCategories] = useState([]);
  const [selectedAuthors, setSelectedAuthors] = useState([]);
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  useEffect(() => {
    const fetchOptions = async () => {
      try {
        setLoading(true);
        setError('');
        const response = await api.get('/preferences/options');

        setSources(response.data.sources);
        setCategories(response.data.categories);
        setAuthors(response.data.authors);
      } catch (error) {
        if (error.status === HttpStatusCode.Unauthorized) {
          navigate("/login");
        } else {
          setError(`An error occurred: ${error.code}`);
        }
      }finally {
        setLoading(false);
      }
    };

    fetchOptions();
  }, [navigate]);

  const handleSubmit = async () => {
    try {
      await api.post(
        '/preferences',
        {
          sources: selectedSources,
          categories: selectedCategories,
          authors: selectedAuthors,
        }
      );
      
      navigate('/feed');
    } catch (error) {
      console.error("Error updating preferences", error);
      alert("Failed to update preferences");
    }
  };

  return (
    <Box sx={{ maxWidth: 600, margin: 'auto', mt: 5 }}>
     {loading && <CircularProgress />}
        
      <Typography variant="h4" mb={3}>Update Your Preferences</Typography>

      {error && <Typography>{error}</Typography>}
      
      <TextField
        select
        label="Select Sources"
        value={selectedSources}
        onChange={(e) => setSelectedSources(e.target.value)}
        SelectProps={{
          multiple: true,
        }}
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
        select
        label="Select Categories"
        value={selectedCategories}
        onChange={(e) => setSelectedCategories(e.target.value)}
        SelectProps={{
          multiple: true,
        }}
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
        label="Select Authors"
        value={selectedAuthors}
        onChange={(e) => setSelectedAuthors(e.target.value)}
        SelectProps={{
          multiple: true,
        }}
        fullWidth
        variant="outlined"
        margin="normal"
      >
        {authors.map((author) => (
          <MenuItem key={uuidv4()} value={author}>
            {author}
          </MenuItem>
        ))}
      </TextField>

      <Button variant="contained" color="primary" onClick={handleSubmit} sx={{ mt: 3 }}>
        Save Preferences
      </Button>
    </Box>
  );
};

export default Preferences;
