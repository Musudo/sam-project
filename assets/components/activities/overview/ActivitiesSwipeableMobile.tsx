import * as React from 'react';
import SwipeableViews from 'react-swipeable-views';
import {useTheme} from '@mui/material/styles';
import AppBar from '@mui/material/AppBar';
import Tabs from '@mui/material/Tabs';
import Tab from '@mui/material/Tab';
import Typography from '@mui/material/Typography';
import Box from '@mui/material/Box';
import {IActivity} from "../../../models/IActivity";
import {ActivitiesColumn} from "./ActivitiesColumn";
import {useTranslation} from "react-i18next";
import {allyProps} from "../../../props/MUIElementProps";

interface Props {
	activitiesToday: IActivity[];
	activitiesNextSevenDays: IActivity[];
	activitiesNextThirtyDays: IActivity[];
}

interface TabPanelProps {
	children?: React.ReactNode;
	dir?: string;
	index: number;
	value: number;
}

function TabPanel(props: TabPanelProps) {
	const {children, value, index, ...other} = props;

	return (
		<Typography
			component="div"
			role="tabpanel"
			hidden={value !== index}
			id={`action-tabpanel-${index}`}
			aria-labelledby={`action-tab-${index}`}
			{...other}>
			{value === index && <Box sx={{p: 3}}>{children}</Box>}
		</Typography>
	);
}

export default function ActivitiesSwipeableMobile(props: Props) {
	const theme = useTheme();
	const [value, setValue] = React.useState(0);
	const {t} = useTranslation();

	const handleChange = (event: unknown, newValue: number) => setValue(newValue);

	const handleChangeIndex = (index: number) => setValue(index);

	return (
		<Box sx={{
			bgcolor: '#eaeaee',
			width: '100%',
			position: 'relative',
			minHeight: 200
		}}>
			<AppBar position="static" color="default">
				<Tabs
					value={value}
					onChange={handleChange}
					indicatorColor="primary"
					textColor="primary"
					variant="fullWidth"
					aria-label="action tabs example">
					<Tab label={t('Activities overview page.Today')} {...allyProps(0)} />
					<Tab label={t('Activities overview page.Next 7 days')} {...allyProps(1)} />
					<Tab label={t('Activities overview page.Next 30 days')} {...allyProps(2)} />
				</Tabs>
			</AppBar>
			<SwipeableViews axis={theme.direction === 'rtl' ? 'x-reverse' : 'x'}
							index={value}
							onChangeIndex={handleChangeIndex}>
				<TabPanel value={value} index={0} dir={theme.direction}>
					<ActivitiesColumn activities={props.activitiesToday} columnName={null}/>
				</TabPanel>
				<TabPanel value={value} index={1} dir={theme.direction}>
					<ActivitiesColumn activities={props.activitiesNextSevenDays} columnName={null}/>
				</TabPanel>
				<TabPanel value={value} index={2} dir={theme.direction}>
					<ActivitiesColumn activities={props.activitiesNextThirtyDays} columnName={null}/>
				</TabPanel>
			</SwipeableViews>
		</Box>
	);
}