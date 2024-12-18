import React from 'react';
import {ListItemIcon, Menu, MenuItem} from '@mui/material';
import {useTranslation} from 'react-i18next';
import {IUser} from '../../models/IUser';
import {Logout} from '@mui/icons-material';
import LoginIcon from '@mui/icons-material/Login';

interface Props {
	mainMenuAnchorEl: HTMLElement | null;
	handleMainMenuClose: () => void;
	isMainMenuOpen: boolean;
	user: IUser | null;
	mainMenuId: string;
}

const MainMenu: React.FC<Props> = (props: Props) => {
	const {t} = useTranslation();

	return (
		<Menu
			anchorEl={props.mainMenuAnchorEl}
			anchorOrigin={{
				vertical: 'bottom',
				horizontal: 'right',
			}}
			id={props.mainMenuId}
			keepMounted
			transformOrigin={{
				vertical: 'top',
				horizontal: 'right',
			}}
			open={props.isMainMenuOpen}
			onClose={props.handleMainMenuClose}
			PaperProps={{
				elevation: 0,
				sx: {
					overflow: 'visible',
					filter: 'drop-shadow(0px 2px 8px rgba(0,0,0,0.32))',
					'& .MuiAvatar-root': {
						width: 32,
						height: 32,
						ml: -0.5,
						mr: 1,
					},
					'&:before': {
						content: '""',
						display: 'block',
						position: 'absolute',
						top: 0,
						right: 14,
						width: 10,
						height: 10,
						bgcolor: 'background.paper',
						transform: 'translateY(-50%) rotate(45deg)',
						zIndex: 0,
					},
				},
			}}>
			{(props.user && props.user.roles.includes('ROLE_USER')) ? (
				<MenuItem key='logout' component='a' href='/logout'>
					<ListItemIcon>
						<Logout fontSize='small'/>
					</ListItemIcon>
					{t('Common.Logout')}
				</MenuItem>
			) : (
				<MenuItem key='login' component='a' href='/connect/azure'>
					<ListItemIcon>
						<LoginIcon fontSize='small'/>
					</ListItemIcon>
					{t('Common.Login')}
				</MenuItem>
			)}
		</Menu>
	);
};

export default MainMenu;
