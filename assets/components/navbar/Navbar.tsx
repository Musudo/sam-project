import * as React from 'react';
import {SyntheticEvent, useContext, useState} from 'react';
import Box from '@mui/material/Box';
import IconButton from '@mui/material/IconButton';
import MenuIcon from '@mui/icons-material/Menu';
import MoreIcon from '@mui/icons-material/MoreVert';
import Toolbar from '@mui/material/Toolbar';
import Typography from '@mui/material/Typography';
import {useNavigate} from "react-router-dom";
import {Avatar, createTheme, Divider, List, styled, Tab, Tabs} from "@mui/material";
import {unCapitalizeFirstLetter} from "../../utils/StringFormatterUtil";
import UserContext, {IUserContext} from "../../context/UserContext";
import {stringAvatar} from "../../utils/AvatarGeneratorUtil";
import TranslateIcon from '@mui/icons-material/Translate';
import {useTranslation} from "react-i18next";
import ReleaseVersion from "./ReleaseVersion";
import LocaleMenu from "./LocaleMenu";
import MobileMenu from "./MobileMenu";
import MainMenu from "./MainMenu";
import DrawerMenu from "./DrawerMenu";
import {NavbarTypesEnum} from "../../enums/ComponentPropsEnums";
import ChevronLeftIcon from "@mui/icons-material/ChevronLeft";
import {mainListItems, secondaryListItems} from "../dashboard/listItems";
import AppBar, {AppBarProps as MuiAppBarProps} from "@mui/material/AppBar/AppBar";
import MuiAppBar from "@mui/material/AppBar";
import MuiDrawer from "@mui/material/Drawer";

export function Navbar() {
	const [mobileOpen, setMobileOpen] = useState(false);
	const navigate = useNavigate();
	const {user} = useContext<IUserContext>(UserContext);
	const {t, i18n} = useTranslation();

	/* drawer menu configuration >> */
	const navItems = ['Dashboard', 'Activities', 'Contacts'];

	const handleDrawerToggle = () => {
		setMobileOpen(!mobileOpen);
	};
	/* << drawer menu configuration */

	/* locale menu configuration >> */
	const localeMenuId = "primary-locale-menu";
	const [anchorElLocaleMenu, setAnchorElLocale] = useState<null | HTMLElement>(null);

	const handleLocaleMenuOpen = (event: React.MouseEvent<HTMLElement>) => {
		setAnchorElLocale(event.currentTarget);
	};

	const handleLocaleMenuClose = () => {
		setAnchorElLocale(null);
	};
	/* << locale menu configuration */

	/* profile icon and menu configuration >> */
	const mainMenuId = 'primary-account-menu';
	const mobileMenuId = 'primary-account-menu-mobile';

	const [anchorEl, setAnchorEl] = useState<null | HTMLElement>(null);
	const [mobileMoreAnchorEl, setMobileMoreAnchorEl] = useState<null | HTMLElement>(null);

	const isMainMenuOpen = Boolean(anchorEl);
	const isMobileMenuOpen = Boolean(mobileMoreAnchorEl);

	const handleProfileMenuOpen = (event: React.MouseEvent<HTMLElement>) => {
		setAnchorEl(event.currentTarget);
	};

	const handleMobileMenuClose = () => {
		setMobileMoreAnchorEl(null);
	};

	const handleMainMenuClose = () => {
		setAnchorEl(null);
		handleMobileMenuClose();
	};

	const handleMobileMenuOpen = (event: React.MouseEvent<HTMLElement>) => {
		setMobileMoreAnchorEl(event.currentTarget);
	};
	/* << profile icon and menu configuration */

	/* nav tabs highlighting configuration >> */
	const getInitialTab = () => {
		if (window.location.pathname.includes("dashboard")) {
			return 0;
		} else if (window.location.pathname.includes("activities")) {
			return 1;
		} else if (window.location.pathname.includes("contacts")) {
			return 2;
		} else {
			return 0;
		}
	};

	const [activeTab, setActiveTab] = useState(getInitialTab());

	const handleChange = (event: SyntheticEvent, newValue: number) => {
		setActiveTab(newValue);
	};
	/* << nav tabs highlighting configuration */

	return (
		<Box sx={{display: 'flex'}}>
			<AppBar component="nav">
				<Toolbar>
					<Box sx={{flexGrow: {xs: 1, sm: 0}}}>
						<IconButton
							color="inherit"
							aria-label="open drawer"
							edge="start"
							onClick={handleDrawerToggle}
							sx={{mr: 2, display: {sm: 'none'}}}>
							<MenuIcon/>
						</IconButton>
					</Box>
					<Box sx={{flexGrow: 0.75, display: {xs: 'none', sm: 'block'}}}>
						<Typography
							variant="h6"
							component="div">
							Sales Activity Manager
						</Typography>
						<ReleaseVersion type={NavbarTypesEnum.Main}/>
					</Box>
					<Box sx={{flexGrow: 1, display: {xs: 'none', sm: 'block'}}}>
						<Tabs value={activeTab}
							  indicatorColor="secondary"
							  textColor="inherit"
							  centered
							  onChange={handleChange}>
							{navItems.map((item: string) => (
								<Tab label={t('Common.' + `${item}`)}
									 onClick={() => navigate("/" + unCapitalizeFirstLetter(item))}/>
							))}
						</Tabs>
					</Box>
					<Box sx={{flexGrow: 1, display: {xs: 'none', sm: 'block'}}}>
					</Box>
					<Box sx={{display: {xs: 'none', md: 'block'}}}>
						<IconButton
							size="large"
							aria-label="change language"
							aria-controls={localeMenuId}
							onClick={handleLocaleMenuOpen}
							color="inherit"
						>
							<TranslateIcon/>
						</IconButton>
						<IconButton
							size="large"
							edge="end"
							aria-label="account of current user"
							aria-controls={mainMenuId}
							aria-haspopup="true"
							onClick={handleProfileMenuOpen}
							color="inherit"
						>
							<Avatar>
								{user ? stringAvatar(`${user.firstName} ${user.lastName}`).children.toString() : ""}
							</Avatar>
						</IconButton>
					</Box>
					<Box sx={{display: {xs: 'flex', md: 'none'}}}>
						<IconButton
							size="large"
							aria-label="show more"
							aria-controls={mobileMenuId}
							aria-haspopup="true"
							onClick={handleMobileMenuOpen}
							color="inherit">
							<MoreIcon/>
						</IconButton>
					</Box>
				</Toolbar>
			</AppBar>
			<LocaleMenu localeMenuAnchorEl={anchorElLocaleMenu} handleLocaleMenuClose={handleLocaleMenuClose}
						language={i18n.language} localeMenuId={localeMenuId}/>
			<MobileMenu mobileMoreAnchorEl={mobileMoreAnchorEl} handleMobileMenuClose={handleMobileMenuClose}
						handleProfileMenuOpen={handleProfileMenuOpen} handleLocaleMenuOpen={handleLocaleMenuOpen}
						isMobileMenuOpen={isMobileMenuOpen} mobileMenuId={mobileMenuId} user={user}/>
			<MainMenu mainMenuAnchorEl={anchorEl} handleMainMenuClose={handleMainMenuClose} user={user}
					  isMainMenuOpen={isMainMenuOpen} mainMenuId={mainMenuId}/>
			<Box component="nav">
				<DrawerMenu mobileOpen={mobileOpen} setMobileOpen={setMobileOpen}
							handleDrawerToggle={handleDrawerToggle} navItems={navItems}/>
			</Box>
			<Toolbar/>
		</Box>
	);
}