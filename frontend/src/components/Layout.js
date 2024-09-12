import Header from './Header';
import { Outlet } from 'react-router-dom';
import { Box } from '@mui/material';

const Layout = () => {
  return (
    <Box>
      <Header />
      <Box component="main" sx={{ padding: 3 }}>
        <Outlet />
      </Box>
    </Box>
  );
};

export default Layout;