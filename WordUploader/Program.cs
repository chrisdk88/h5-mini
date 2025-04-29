using System.Threading.Tasks;

internal class Program
{
    static async Task Main(string[] args)
    {
        await WordUploader.UploadWordsAsync();
    }
}
