import {Divider, IconButton, List, styled, Toolbar, useMediaQuery} from "@mui/material";
import ChevronLeft from "@mui/icons-material/ChevronLeft";
import {mainListItems, secondaryListItems} from "./listItems";
import React, {useState} from "react";
import {AppBarProps as MuiAppBarProps} from "@mui/material/AppBar/AppBar";
import MuiDrawer from "@mui/material/Drawer";
import {ChevronRight} from "@mui/icons-material";

interface AppBarProps extends MuiAppBarProps {
	open?: boolean;
}

const drawerWidth: number = 240;
const appBarHeight = 64;

const Drawer = styled(MuiDrawer, {shouldForwardProp: (prop: string) => prop !== 'open'})(
	({theme, open}) => ({
		'& .MuiDrawer-paper': {
			position: 'relative',
			// top: appBarHeight,
			whiteSpace: 'nowrap',
			width: drawerWidth,
			transition: theme.transitions.create('width', {
				easing: theme.transitions.easing.sharp,
				duration: theme.transitions.duration.enteringScreen,
			}),
			boxSizing: 'border-box',
			...(!open && {
				overflowX: 'hidden',
				transition: theme.transitions.create('width', {
					easing: theme.transitions.easing.sharp,
					duration: theme.transitions.duration.leavingScreen,
				}),
				width: theme.spacing(7),
				[theme.breakpoints.up('sm')]: {
					width: theme.spacing(9),
				},
			}),
		},
	}),
);

export function CustomDrawer() {
	const [open, setOpen] = useState(true);
	const isMobile = useMediaQuery('(max-width: 600px)');

	const toggleDrawer = () => setOpen(!open);

	return (
		<Drawer variant="permanent" open={!isMobile ? open : false}>
			<Toolbar
				sx={{
					display: 'flex',
					alignItems: 'center',
					justifyContent: 'flex-end',
					px: [1],
				}}>
				{!isMobile && (
					<IconButton onClick={toggleDrawer}>
						{open ? <ChevronLeft/> : <ChevronRight/>}
					</IconButton>
				)}
			</Toolbar>
			<Divider/>
			<List component="nav">
				{mainListItems}
				<Divider sx={{my: 1}}/>
				{secondaryListItems}
			</List>
		</Drawer>
	);
}