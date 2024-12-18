import React from 'react';
import {Avatar, IconButton, Menu, MenuItem} from "@mui/material";
import {useTranslation} from "react-i18next";
import {stringAvatar} from "../../utils/AvatarGeneratorUtil";
import TranslateIcon from "@mui/icons-material/Translate";
import {IUser} from "../../models/IUser";

interface Props {
	mobileMoreAnchorEl: HTMLElement | null;
	handleMobileMenuClose: () => void;
	handleProfileMenuOpen: (event: React.MouseEvent<HTMLElement>) => void;
	handleLocaleMenuOpen: (event: React.MouseEvent<HTMLElement>) => void;
	isMobileMenuOpen: boolean;
	user: IUser | null;
	mobileMenuId: string;
}

const MobileMenu: React.FC<Props> = (props: Props) => {
	const {t} = useTranslation();

	return (
		<Menu
			anchorEl={props.mobileMoreAnchorEl}
			anchorOrigin={{
				vertical: 'bottom',
				horizontal: 'right',
			}}
			id={props.mobileMenuId}
			keepMounted
			transformOrigin={{
				vertical: 'bottom',
				horizontal: 'right',
			}}
			open={props.isMobileMenuOpen}
			onClose={props.handleMobileMenuClose}>
			<MenuItem key='profile mobile menu' onClick={props.handleProfileMenuOpen}>
				<IconButton
					size="small"
					aria-label="profile mobile menu"
					aria-controls={props.mobileMenuId}
					aria-haspopup="true"
					color="inherit">
					<Avatar>
						{stringAvatar(`${props.user?.firstName} ${props.user?.lastName}`).children.toString()}
					</Avatar>
				</IconButton>
				<p>{t('Common.User menu')}</p>
			</MenuItem>
			<MenuItem key='locale mobile menu' onClick={props.handleLocaleMenuOpen}>
				<IconButton
					size="large"
					aria-label="locale mobile menu"
					aria-controls="primary-locale-menu"
					aria-haspopup="true"
					color="inherit">
					<TranslateIcon/>
				</IconButton>
				{t('Common.Active language')}
			</MenuItem>
		</Menu>
	);
};

export default MobileMenu;
