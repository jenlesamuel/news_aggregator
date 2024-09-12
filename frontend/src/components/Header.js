import { useContext } from 'react';
import { AppBar, Toolbar, Button, Typography, Box } from '@mui/material';
import { Link } from 'react-router-dom';
import { AuthContext } from '../context/AuthContext';

const Header = () => {
  const { token, logout } = useContext(AuthContext);

  return (
    <AppBar position="static">
      <Toolbar>
        <Typography variant="h6" component="div" sx={{ flexGrow: 1 }}>
          News Aggregator
        </Typography>

        {token && (
          <Box display="flex">
            <Button component={Link} to="/" color="inherit">Home</Button>
            <Button component={Link} to="/search" color="inherit">Search</Button>
            <Button component={Link} to="/settings" color="inherit">Settings</Button>
            <Button color="inherit" onClick={logout}>Logout</Button>
          </Box>
        )}

        {!token && (
          <Box display="flex">
            <Button component={Link} to="/login" color="inherit">Login</Button>
            <Button component={Link} to="/register" color="inherit">Register</Button>
          </Box>
        )}
      </Toolbar>
    </AppBar>
  );
};

export default Header;
