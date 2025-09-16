using Microsoft.AspNetCore.Mvc;

namespace TranslatorAPI.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class TranslatorController : ControllerBase
    {
        private bool IsTranslatorAvailable()
        {
            return true;
        }
        
        [HttpGet]
        public IActionResult GetTranslationStatus()
        {
            if (IsTranslatorAvailable())
                return Ok("Список переводчиков готов");
            else
                return Ok("Нет свободных переводчиков");
        }
    }
}
