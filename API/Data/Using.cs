// ------- Microsoft ------- //
global using Microsoft.AspNetCore.Authentication.JwtBearer;
global using Microsoft.AspNetCore.Authorization;
global using Microsoft.IdentityModel.Tokens;
global using Microsoft.EntityFrameworkCore;
global using Microsoft.AspNetCore.Mvc;
global using Microsoft.OpenApi.Models;

// ------- System ------- //
global using System.ComponentModel.DataAnnotations.Schema;
global using System.ComponentModel.DataAnnotations;
global using System.IdentityModel.Tokens.Jwt;
global using System.Text.RegularExpressions;
global using System.Security.Claims;
global using System.Text.Json;
global using System.Text;
global using System.Data;

// ------- API ------- //
global using API.Models.Leaderboard;
global using API.Models.Common;
global using API.Models.Gamedle;
global using API.Models.Loldle;
global using API.Models.Wordle;
global using API.Models;
global using API.Data;