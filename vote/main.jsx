const React = require("react");
const {
    AppBar,
    Button,
    IconButton,
    Menu,
    MenuItem,
    Paper,
    Switch,
    Toolbar,
    Typography,
    withStyles
} = require("@material-ui/core");
const {
    Menu: MenuIcon,
    AccountCircle: AccountIcon
} = require("@material-ui/icons");
const { connect } = require("react-redux");

const LoginDialog = require("../../auth/components/login-dialog.jsx");
const MainDrawer = require("./drawer.jsx");

const actions = { ...require("../actions.js"), ...require("../../auth/actions.js") };

const styles = theme => ({
    menuButton: {
        margin: [[0, 12, 0, -12]]
    },
    mainBarTitle: {
        flexGrow: 1
    },
    mainDocument: {
        ...theme.mixins.gutters(),
        paddingTop: theme.spacing.unit * 2,
        paddingBottom: theme.spacing.unit * 2,
        maxWidth: 760,
        margin: [[theme.spacing.unit * 3, "auto", theme.spacing.unit * 3, "auto"]],
        minHeight: 800
    }
});

module.exports = withStyles(styles)(connect(state => ({ main: state.main, auth: state.auth }), actions)(props => (
    <div>
      <MainDrawer />
      <AppBar position="static">
        <Toolbar>
          <IconButton color="inherit" className={props.classes.menuButton} onClick={props.toggleDrawer}>
            <MenuIcon />
          </IconButton>
          <Typography variant="title" color="inherit" className={props.classes.mainBarTitle}>
            Dashboard
          </Typography>
          {props.auth.isLoggedIn ?
           <div>
             <IconButton color="inherit" onClick={event => props.toggleAccountMenu(event.target)}>
               <AccountIcon />
             </IconButton>
             <Menu anchorEl={props.main.anchorElement} open={Boolean(props.main.anchorElement)} onClose={props.toggleAccountMenu}>
               <MenuItem>Profile</MenuItem>
               <MenuItem>Logout</MenuItem>
             </Menu>
           </div> :
           <Button color="inherit" onClick={props.toggleLoginDialog}>Login</Button>}
        </Toolbar>
      </AppBar>
      <LoginDialog />
      <Paper elevation={1} className={props.classes.mainDocument}>
        <Typography variant="title">
          This is the title of the document.
        </Typography>
      </Paper>
    </div>
)));
