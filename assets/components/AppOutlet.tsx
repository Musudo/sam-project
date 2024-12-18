import {Outlet} from 'react-router-dom';
import {Navbar} from './navbar/Navbar';
import {Box, Container} from '@mui/material';
import React from 'react';

export default function AppOutlet() {

	return (
		<Box sx={{
			display: 'flex',
			flexDirection: 'column',
			backgroundColor: '#FAFAFB',
			minHeight: '100vh'
		}}>
			<Navbar/>
			<Outlet/>
		</Box>
	);
}
