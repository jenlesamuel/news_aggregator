import { useState, useContext } from 'react';
import { TextField, Button, Box, Typography } from '@mui/material';
import { AuthContext } from '../../context/AuthContext';
import { useNavigate } from 'react-router-dom';

const Login = () => {
  const navigate = useNavigate();
  const { login } = useContext(AuthContext);
  const [formData, setFormData] = useState({
    email: '',
    password: ''
  });
  const [errors, setErrors] = useState({});
  const [serverError, setServerError] = useState("");

  const validateForm = () => {
    const newErrors = {};
    
    // Validate email
    if (!formData.email) {
      newErrors.email = "Email is required";
    } else if (!/\S+@\S+\.\S+/.test(formData.email)) {
      newErrors.email = "Email is invalid";
    }

    // Validate password
    if (!formData.password) {
      newErrors.password = "Password is required";
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (validateForm()) {
      try {
        await login(formData.email, formData.password);
        navigate("/feed");
      } catch (error) {
        setServerError(`An error occurred: ${error.code}`);
      }
    }
  };

  return (
    <Box sx={{ padding: 2 }}>
      <Typography variant="h4">Login</Typography>
      {serverError && <Typography>{serverError}</Typography>}
      <form onSubmit={handleSubmit}>
        <TextField
          label="Email"
          value={formData.email}
          onChange={(e) => setFormData({ ...formData, email: e.target.value })}
          error={!!errors.email}
          helperText={errors.email}
          fullWidth
          margin="normal"
        />
        <TextField
          label="Password"
          type="password"
          value={formData.password}
          onChange={(e) => setFormData({ ...formData, password: e.target.value })}
          error={!!errors.password}
          helperText={errors.password}
          fullWidth
          margin="normal"
        />
        <Button type="submit" variant="contained" color="primary" fullWidth>
          Login
        </Button>
      </form>
    </Box>
  );
};

export default Login;
