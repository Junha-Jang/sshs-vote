const React = require("react");
const {
    Collapse,
    Drawer,
    List,
    ListItem,
    ListItemIcon,
    ListItemText,
    withStyles
} = require("@material-ui/core");
const {
    CompareArrows: MoveSeatIcon,
    ExpandLess: ExpandLessIcon,
    ExpandMore: ExpandMoreIcon,
    Restaurant: MealIcon,
    School: SchoolIcon
} = require("@material-ui/icons");
const { Link } = require("react-router-dom");
const { connect } = require("react-redux");

const actions = require("../actions.js");

const styles = theme => ({
    drawer: {
        width: 256
    },
    nestedListItem: {
        paddingLeft: theme.spacing.unit * 4
    }
});

const ListItemButton = ({ children, ...props }) => (
    <li>
      <ListItem button {...props}>
        {children}
      </ListItem>
    </li>
);

const CollapsableListItem = ({ children, header, open, onClick, ...props }) => (
    <React.Fragment>
      <ListItemButton onClick={onClick} {...props}>
        {header}
      </ListItemButton>
      <Collapse component="li" in={open}>
        {children}
      </Collapse>
    </React.Fragment>
);

const ExpandIcon = ({ expandable }) => expandable ? <ExpandMoreIcon /> : <ExpandLessIcon />;

module.exports = withStyles(styles)(connect(state => ({ main: state.main }), actions)(props => (
    <Drawer open={props.main.isDrawerOpen} onClose={props.toggleDrawer}>
      <List component="nav" className={props.classes.drawer}>
        <ListItemButton>
          <ListItemIcon>
            <MealIcon />
          </ListItemIcon>
          <ListItemText>학교 급식</ListItemText>
        </ListItemButton>
        <CollapsableListItem open={props.main.isNoticeOpen} onClick={e => props.toggleDrawerItem("Notice")} header={
            <React.Fragment>
              <ListItemIcon>
                <SchoolIcon />
              </ListItemIcon>
              <ListItemText>공지사항</ListItemText>
              <ExpandIcon expandable={!props.main.isNoticeOpen}/>
            </React.Fragment>
        }>
          {[{ name: "연구", to: "/notice/research" },{ name: "행사", to: "/notice/event" }, { name: "학사일정", to: "/notice/calendar"}].map(({ name, to }) => (
              <li>
                <ListItem button className={props.classes.nestedListItem} component={Link} to={to}>
                  <ListItemText>{name}</ListItemText>
                </ListItem>
              </li>
          ))}
        </CollapsableListItem>
        <CollapsableListItem open={props.main.isMoveSeatOpen} onClick={e => props.toggleDrawerItem("MoveSeat")} header={
            <React.Fragment>
              <ListItemIcon>
                <MoveSeatIcon />
              </ListItemIcon>
              <ListItemText>이석</ListItemText>
              <ExpandIcon expandable={!props.main.isNoticeOpen}/>
            </React.Fragment>
        }>
          {[{ name: "1학년 공강실", to: "/move-seat/grade/1" }, { name: "2학년 공강실", to: "/move-seat/grade/2" }, { name: "3학년 공강실", to: "/move-seat/grade/3" }, { name: "세미나실", to: "/move-seat/seminar" }, { name: "단체 이석", to: "/move-seat/group" }, { name: "이석 명단 확인", to: "/move-seat/list" }].map(({ name, to }) => (
              <li>
                <ListItem button className={props.classes.nestedListItem} component={Link} to={to}>
                  <ListItemText>{name}</ListItemText>
                </ListItem>
              </li>
          ))}
        </CollapsableListItem>
      </List>
    </Drawer>
)));
