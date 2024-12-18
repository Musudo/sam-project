import React from 'react';
import {Menu, MenuItem} from "@mui/material";
import Cookies from "js-cookie";
import {useTranslation} from "react-i18next";
import {Languages} from "../../constants/constants";

interface Props {
	localeMenuAnchorEl: HTMLElement | null;
	handleLocaleMenuClose: () => void;
	language: string;
	localeMenuId: string;
}

const LocaleMenu: React.FC<Props> = (props: Props) => {
	const {i18n} = useTranslation();

	const handleLocaleMenuChange = (event: any) => {
		i18n.changeLanguage(event.currentTarget.dataset.lang);

		Cookies.set('lang', event.currentTarget.dataset.lang, {expires: 7});
	}

	return (
		<Menu
			id={props.localeMenuId}
			anchorEl={props.localeMenuAnchorEl}
			anchorOrigin={{
				vertical: 'bottom',
				horizontal: 'right',
			}}
			keepMounted
			transformOrigin={{
				vertical: 'top',
				horizontal: 'right',
			}}
			open={Boolean(props.localeMenuAnchorEl)}
			onClose={props.handleLocaleMenuClose}
			PaperProps={{
				elevation: 0,
				sx: {
					overflow: 'visible',
					filter: 'drop-shadow(0px 2px 8px rgba(0,0,0,0.32))',
					mt: 2,
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
			{Languages.map((lang: {
				shorthand: string,
				longhand: string
			}, index: number) => (
				<MenuItem key={index} data-lang={lang.shorthand} onClick={handleLocaleMenuChange}
						  selected={props.language === lang.shorthand}>
					{lang.longhand}
				</MenuItem>
			))}
		</Menu>
	);
};

export default LocaleMenu;
